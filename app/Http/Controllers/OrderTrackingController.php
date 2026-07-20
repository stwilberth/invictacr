<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function show()
    {
        if (auth()->check()) {
            $invoices = Invoice::where('client_email', auth()->user()->email)
                ->with('items.product')
                ->orderByDesc('created_at')
                ->get();

            return view('pages.order-tracking', compact('invoices'));
        }

        return view('pages.order-tracking');
    }

    public function search(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string',
            'email' => 'required|email',
        ]);

        $invoice = Invoice::where('invoice_number', $request->invoice_number)
            ->where('client_email', $request->email)
            ->with('items.product')
            ->first();

        if (!$invoice) {
            return back()->with('error', 'No se encontró ningún pedido con esos datos. Verifica el número de factura y el correo electrónico.');
        }

        return view('pages.order-tracking', compact('invoice'));
    }
}
