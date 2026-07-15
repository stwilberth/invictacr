<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function download(Invoice $invoice)
    {
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        return $pdf->download("Recibo_{$invoice->client_name}_{$invoice->created_at->format('Y-m-d')}.pdf");
    }

    public function preview(Invoice $invoice)
    {
        return view('pdf.invoice', compact('invoice'));
    }
}
