<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        if (!$this->verifyTurnstile($request)) {
            return back()
                ->withErrors(['turnstile' => 'No pudimos verificar que sos humano. Intenta de nuevo.'])
                ->withInput();
        }

        Subscriber::firstOrCreate(
            ['email' => $request->email],
            ['active' => true]
        );

        return back()->with('subscriber_success', '¡Suscripción exitosa! Pronto recibirás nuestras novedades.');
    }

    private function verifyTurnstile(Request $request): bool
    {
        $secret = config('services.turnstile.secret_key');

        if (empty($secret)) {
            return true;
        }

        $token = $request->input('cf-turnstile-response');

        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->withOptions(app()->environment('local') ? ['verify' => false] : [])
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

            return (bool) ($response->json('success') ?? false);
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
