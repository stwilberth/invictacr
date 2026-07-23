<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function create()
    {
        return view("auth.register");
    }

    public function store(Request $request)
    {
        if (!$this->verifyTurnstile($request)) {
            return back()
                ->withErrors([
                    "turnstile" => "No pudimos verificar que sos humano. Intenta de nuevo.",
                ])
                ->withInput(
                    $request->except("password", "password_confirmation"),
                );
        }

        $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users",
            ],
            "telefono" => ["required", "string", "max:20"],
            "password" => ["required", "string", "min:8", "confirmed"],
        ]);

        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "telefono" => $request->telefono,
                "password" => $request->password,
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return back()
                ->withErrors([
                    "email" => "Ya existe una cuenta con este correo.",
                ])
                ->withInput(
                    $request->except("password", "password_confirmation"),
                );
        }

        Auth::login($user);

        // Vincular perfil de visitante anónimo con el nuevo usuario
        try {
            \App\Models\Visitor::currentFromRequest($request)?->linkToUser($user, $request->telefono);
        } catch (\Throwable $e) {
            report($e);
        }

        $oldSessionId = $request->session()->getId();
        $request->session()->regenerate();

        $guestCart = Cart::where('session_id', $oldSessionId)->whereNull('user_id')->first();
        if ($guestCart) {
            $userCart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['session_id' => $request->session()->getId()]
            );
            app(CartService::class)->mergeCarts($guestCart, $userCart);
            $guestCart->delete();
        }

        return redirect("/dashboard");
    }

    /**
     * Verifica el token de Cloudflare Turnstile.
     * Si no hay claves configuradas, se omite la verificación.
     */
    private function verifyTurnstile(Request $request): bool
    {
        $secret = config("services.turnstile.secret_key");

        if (empty($secret)) {
            return true;
        }

        $token = $request->input("cf-turnstile-response");

        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->withOptions(app()->environment("local") ? ["verify" => false] : [])
                ->post("https://challenges.cloudflare.com/turnstile/v0/siteverify", [
                    "secret" => $secret,
                    "response" => $token,
                    "remoteip" => $request->ip(),
                ]);

            return (bool) ($response->json("success") ?? false);
        } catch (\Exception $e) {
            report($e);

            return false;
        }
    }
}
