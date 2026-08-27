<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\VisitorEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VisitorTrackController extends Controller
{
    private const ALLOWED_TYPES = ['page_view', 'product_view', 'search', 'whatsapp_click', 'add_to_cart', 'cta_click'];

    public function event(Request $request)
    {
        $visitor = Visitor::currentFromRequest($request);
        $isNewVisitor = false;

        // Si el visitor aún no existe, lo creamos
        if (!$visitor) {
            $ua = (string) $request->userAgent();
            if ($ua === '' || Visitor::isBot($ua)) {
                return response()->json(['ok' => false], 200);
            }

            $visitor = Visitor::createFromRequest($request);
            $isNewVisitor = true;
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'max:30'],
            'url' => ['nullable', 'string', 'max:1000'],
            'title' => ['nullable', 'string', 'max:255'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'query' => ['nullable', 'string', 'max:255'],
            'cta' => ['nullable', 'string', 'max:50'],
        ]);

        if (!in_array($validated['type'], self::ALLOWED_TYPES, true)) {
            return response()->json(['ok' => false], 200);
        }

        $meta = null;
        if ($validated['type'] === 'search' && !empty($validated['query'])) {
            $meta = ['query' => $validated['query']];
        }
        if ($validated['type'] === 'cta_click' && !empty($validated['cta'])) {
            $meta = ['cta' => $validated['cta']];
        }

        $event = VisitorEvent::create([
            'visitor_id' => $visitor->id,
            'session_id' => $request->session()->getId(),
            'type' => $validated['type'],
            'url' => isset($validated['url']) ? Str::limit($validated['url'], 1000, '') : null,
            'page_title' => $validated['title'] ?? null,
            'product_id' => $validated['product_id'] ?? null,
            'meta' => $meta,
            'created_at' => now(),
        ]);

        $updates = ['last_seen_at' => now()];

        if (in_array($validated['type'], ['page_view', 'product_view'], true)) {
            $updates['pageviews_count'] = $visitor->pageviews_count + 1;
        }

        if ($validated['type'] === 'whatsapp_click') {
            $updates['whatsapp_clicked_at'] = now();
        }

        $visitor->fill($updates)->save();

        $response = response()->json(['ok' => true, 'event_id' => $event->id]);

        if ($isNewVisitor) {
            $response->headers->setCookie(
                cookie(
                    Visitor::COOKIE_NAME,
                    $visitor->uuid,
                    60 * 24 * 365 * 2,
                    '/',
                    null,
                    $request->isSecure(),
                    true,
                    false,
                    'Lax'
                )
            );
        }

        return $response;
    }

    public function heartbeat(Request $request)
    {
        $visitor = Visitor::currentFromRequest($request);

        if (!$visitor) {
            return response()->json(['ok' => false], 200);
        }

        $validated = $request->validate([
            'event_id' => ['required', 'integer'],
            'seconds' => ['required', 'integer', 'min:1', 'max:300'],
        ]);

        $event = VisitorEvent::where('id', $validated['event_id'])
            ->where('visitor_id', $visitor->id)
            ->whereIn('type', ['page_view', 'product_view'])
            ->first();

        if (!$event) {
            return response()->json(['ok' => false], 200);
        }

        $seconds = $validated['seconds'];

        $event->duration_seconds = ($event->duration_seconds ?? 0) + $seconds;
        $event->updated_at = now();
        $event->save();

        $visitor->total_time_seconds = $visitor->total_time_seconds + $seconds;
        $visitor->last_seen_at = now();
        $visitor->save();

        return response()->json(['ok' => true]);
    }
}
