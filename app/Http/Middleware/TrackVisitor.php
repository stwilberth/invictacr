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
        if (!$this->shouldTrack($request)) {
            return $next($request);
        }

        // Resolver ANTES de renderizar para que $visitorUuid esté
        // disponible en las vistas (ej: código ref de WhatsApp).
        try {
            $visitor = $this->resolveVisitor($request);

            if ($visitor) {
                View::share('visitorUuid', $visitor->uuid);
                View::share('isReturningVisitor', $visitor->visits_count > 3);
            }
        } catch (\Throwable $e) {
            report($e);
            $visitor = null;
        }

        $response = $next($request);

        if ($visitor && !$request->cookies->has(Visitor::COOKIE_NAME)) {
            try {
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
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        // Personal interno: no se registra su navegación
        if ($request->user()?->isStaff()) {
            return false;
        }

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

        // Sin cookie válida: los navegadores in-app de FB/IG y los privados
        // pierden cookies y harían que cada página cuente como visitante
        // nuevo. Reutilizar la sesión con misma IP + user_agent dentro de la
        // ventana de sesión (fallback de sesionización).
        $ip = $request->header('CF-Connecting-IP') ?: $request->ip();
        $ua = \Illuminate\Support\Str::limit((string) $request->userAgent(), 1000, '');

        $visitor = Visitor::where('ip', $ip)
            ->where('user_agent', $ua)
            ->where('last_seen_at', '>=', $now->copy()->subMinutes(self::SESSION_GAP_MINUTES))
            ->orderByDesc('last_seen_at')
            ->first();

        if ($visitor) {
            $updates = [];

            if (!$visitor->last_seen_at || $visitor->last_seen_at->diffInMinutes($now) >= self::SESSION_GAP_MINUTES) {
                $updates['visits_count'] = $visitor->visits_count + 1;
            }

            if (!$visitor->last_seen_at || $visitor->last_seen_at->diffInSeconds($now) >= 60 || !empty($updates)) {
                $updates['last_seen_at'] = $now;
                $visitor->fill($updates)->save();
            }

            if ($request->user() && !$visitor->user_id) {
                $visitor->linkToUser($request->user());
            }

            return $visitor;
        }

        // Anti-ráfaga: si esta IP ya creó demasiados visitantes nuevos sin
        // cookies en poco tiempo es un scraper/AI, no guardar nada más de ella.
        $recentFromIp = Visitor::where('ip', $ip)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();
        if ($recentFromIp >= 10) {
            return null;
        }

        return Visitor::createFromRequest($request);
    }
}
