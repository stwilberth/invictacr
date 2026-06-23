<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberApiController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        Subscriber::firstOrCreate(
            ['email' => $request->email],
            ['active' => true]
        );

        return response()->json(['success' => true, 'message' => 'Suscripción exitosa']);
    }

    public function list()
    {
        return Subscriber::where('active', true)->latest()->get(['id', 'email', 'created_at']);
    }
}
