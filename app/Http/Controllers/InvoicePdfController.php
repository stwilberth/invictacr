<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function download(Invoice $invoice)
    {
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        return $pdf->download("factura-{$invoice->invoice_number}.pdf");
    }

    public function preview(Invoice $invoice)
    {
        return view('pdf.invoice', compact('invoice'));
    }
}
