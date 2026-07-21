<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view("auth.register");
    }

    public function store(Request $request)
    {
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
}
