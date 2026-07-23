<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Vincular perfil de visitante anónimo con el usuario
            try {
                \App\Models\Visitor::currentFromRequest($request)?->linkToUser(Auth::user());
            } catch (\Throwable $e) {
                report($e);
            }

            $oldSessionId = $request->session()->getId();
            $request->session()->regenerate();

            $guestCart = Cart::where('session_id', $oldSessionId)->whereNull('user_id')->first();
            if ($guestCart) {
                $userCart = Cart::firstOrCreate(
                    ['user_id' => Auth::id()],
                    ['session_id' => $request->session()->getId()]
                );
                app(CartService::class)->mergeCarts($guestCart, $userCart);
                $guestCart->delete();
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
