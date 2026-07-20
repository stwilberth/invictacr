<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartService::class);
    }

    public function boot(): void
    {
        RateLimiter::for("search", function (Request $request) {
            if (!$request->filled("q")) {
                return Limit::none();
            }

            $key = $request->user()?->id ?: $request->ip();

            return Limit::perMinute(10)->by("search:" . $key)
                ->response(function () {
                    return back()->with("error", "Demasiadas búsquedas. Intenta de nuevo en un minuto.");
                });
        });
    }
}
