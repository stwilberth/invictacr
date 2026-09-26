<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Anti-ráfagas: limita solicitudes por IP en las rutas públicas.
 * Los scrapers/AI crean cientos de "visitas" en minutos (sin cookies),
 * esto los corta antes de consumir servidor.
 */
class RateLimitWeb
{
    private const MAX_PER_MINUTE = 60;

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin*', 'livewire*', 'track*', 'api*', 'up', 'login', 'logout', 'paypal*', 'webhook*')) {
            return $next($request);
        }

        // Personal interno: sin límite
        if ($request->user()?->isStaff()) {
            return $next($request);
        }

        $key = 'webrl:' . ($request->header('CF-Connecting-IP') ?: $request->ip());

        if (RateLimiter::tooManyAttempts($key, self::MAX_PER_MINUTE)) {
            return response('Demasiadas solicitudes. Intenta más tarde.', 429, [
                'Retry-After' => RateLimiter::availableIn($key),
            ]);
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
