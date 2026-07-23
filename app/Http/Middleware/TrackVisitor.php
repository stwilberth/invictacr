<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Minutos de inactividad para considerar una nueva visita.
     */
    private const SESSION_GAP_MINUTES = 30;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$this->shouldTrack($request)) {
            return $response;
        }

        try {
            $visitor = $this->resolveVisitor($request);

            if ($visitor) {
                View::share('visitorUuid', $visitor->uuid);

                if (!$request->cookies->has(Visitor::COOKIE_NAME)) {
                    $response->headers->setCookie(
                        cookie(
                            Visitor::COOKIE_NAME,
                            $visitor->uuid,
                            60 * 24 * 365 * 2, // 2 años
                            '/',
                            null,
                            $request->isSecure(),
                            true, // httpOnly
                            false,
                            'Lax'
                        )
                    );
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return false;
        }

        if ($request->is('admin*', 'api*', 'livewire*', 'track*', 'up', 'login', 'registro', 'logout', 'checkout*', 'paypal*', 'carrito*')) {
            return false;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return false;
        }

        $ua = (string) $request->userAgent();
        if ($ua === '' || Visitor::isBot($ua)) {
            return false;
        }

        return true;
    }

    private function resolveVisitor(Request $request): ?Visitor
    {
        $uuid = $request->cookies->get(Visitor::COOKIE_NAME);
        $now = now();

        if ($uuid && is_string($uuid) && strlen($uuid) <= 36) {
            $visitor = Visitor::where('uuid', $uuid)->first();

            if ($visitor) {
                $updates = [];

                // Nueva visita si pasaron más de X minutos
                if (!$visitor->last_seen_at || $visitor->last_seen_at->diffInMinutes($now) >= self::SESSION_GAP_MINUTES) {
                    $updates['visits_count'] = $visitor->visits_count + 1;
                }

                // Throttle: máximo 1 escritura por minuto
                if (!$visitor->last_seen_at || $visitor->last_seen_at->diffInSeconds($now) >= 60 || !empty($updates)) {
                    $updates['last_seen_at'] = $now;
                    $visitor->fill($updates)->save();
                }

                if ($request->user() && !$visitor->user_id) {
                    $visitor->linkToUser($request->user());
                }

                return $visitor;
            }
        }

        return Visitor::createFromRequest($request);
    }
}
