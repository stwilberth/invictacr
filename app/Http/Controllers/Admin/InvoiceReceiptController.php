<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abono;
use App\Models\Invoice;
use App\Models\InvoiceReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceReceiptController extends Controller
{
    private const CDN_FALLBACK_NOTE = null;

    /**
     * Crear abono con comprobante opcional (formulario clásico).
     */
    public function storeAbono(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'receipt' => 'nullable|image|mimes:jpeg,png,webp,gif|max:10240',
        ]);

        $totalAbonado = (float) $invoice->abonos()->sum('amount');
        $saldo = round((float) $invoice->total - $totalAbonado, 2);

        if ($saldo <= 0) {
            return redirect()->route('admin.invoices.detail', $invoice->id)
                ->withErrors(['amount' => 'Esta factura ya está pagada. No se pueden agregar más abonos.'])
                ->withInput();
        }

        if (round((float) $data['amount'], 2) > $saldo) {
            return redirect()->route('admin.invoices.detail', $invoice->id)
                ->withErrors(['amount' => 'El monto supera el saldo pendiente de ₡' . number_format($saldo, 0) . '.'])
                ->withInput();
        }

        $abono = Abono::create([
            'invoice_id' => $invoice->id,
            'amount' => $data['amount'],
            'note' => $data['note'] ?? null,
            'date' => $data['date'] ?? now(),
        ]);

        if ($request->hasFile('receipt')) {
            $this->storeAbonoFile($abono, $request->file('receipt'));
        }

        return redirect()->route('admin.invoices.detail', $invoice->id)
            ->with('message', 'Abono agregado. Abonos: ' . $invoice->abonos->pluck('amount')->sum());
    }

    /**
     * Adjuntar/reemplazar comprobante de un abono existente.
     */
    public function storeAbonoReceipt(Request $request, Invoice $invoice, Abono $abono)
    {
        abort_unless($abono->invoice_id === $invoice->id, 404);

        $request->validateWithBag('abono' . $abono->id, [
            'receipt' => 'required|image|mimes:jpeg,png,webp,gif|max:10240',
        ]);

        $this->storeAbonoFile($abono, $request->file('receipt'));

        return redirect()->route('admin.invoices.detail', $invoice->id)
            ->with('message', 'Comprobante agregado al abono.');
    }

    /**
     * Agregar múltiples comprobantes a la factura (contado).
     */
    public function storeReceipts(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'receipts' => 'required|array|min:1',
            'receipts.*' => 'file|mimes:jpeg,png,webp,gif,pdf|max:10240',
        ]);

        foreach ($data['receipts'] as $file) {
            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $key = "comprobantes/facturas/{$invoice->id}/" . uniqid() . ".{$ext}";
            Storage::disk('r2')->put($key, file_get_contents($file->getRealPath()), 'public');

            InvoiceReceipt::create([
                'invoice_id' => $invoice->id,
                'path' => '/storage/' . $key,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return redirect()->route('admin.invoices.detail', $invoice->id)
            ->with('message', 'Comprobante(s) agregado(s).');
    }

    private function storeAbonoFile(Abono $abono, $file): void
    {
        if ($abono->comprobante_path) {
            $oldKey = preg_replace('#^/?storage/#', '', $abono->comprobante_path);
            if ($oldKey && Storage::disk('r2')->exists($oldKey)) {
                Storage::disk('r2')->delete($oldKey);
            }
        }

        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $key = "comprobantes/abonos/{$abono->id}.{$ext}";
        Storage::disk('r2')->put($key, file_get_contents($file->getRealPath()), 'public');
        $abono->update(['comprobante_path' => '/storage/' . $key]);
    }
}