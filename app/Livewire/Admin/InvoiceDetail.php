<?php

namespace App\Livewire\Admin;

use App\Models\Invoice;
use App\Models\Abono;
use App\Models\InvoiceReceipt;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class InvoiceDetail extends Component
{
    public $invoiceId;
    public $invoice;
    public $editing = false;

    public $client_name, $client_email, $client_phone, $customer_address;
    public $province = '', $canton = '', $distrito = '';
    public $subtotal, $discount, $shipping, $shipping_cost, $total;
    public $status, $shipping_status, $notes;
    public $delivery_date, $delivery_time_start, $delivery_time_end;
    public $location, $needs_bracelet_adjustment, $creation_date;
    public $estimated_utility, $cedula, $issued_at;
    public $issued_date;


    protected function rules()
    {
        return [
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:255',
            'customer_address' => 'nullable|string',
            'province' => 'nullable|string|max:100',
            'canton' => 'nullable|string|max:100',
            'distrito' => 'nullable|string|max:100',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'shipping' => 'required|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string',
            'shipping_status' => 'required|string',
            'notes' => 'nullable|string',
            'delivery_date' => 'nullable|date',
            'delivery_time_start' => 'nullable|string|max:255',
            'delivery_time_end' => 'nullable|string|max:255',
            'location' => 'nullable|string',
            'needs_bracelet_adjustment' => 'boolean',
            'creation_date' => 'nullable|date',
            'issued_date' => 'nullable|date',
            'estimated_utility' => 'nullable|numeric|min:0',
            'cedula' => 'nullable|string|max:255',
        ];
    }

    public function mount($id)
    {
        $this->invoiceId = $id;
        $this->loadInvoice();
    }

    public function loadInvoice()
    {
        $this->invoice = Invoice::with(['items.product', 'abonos', 'receipts'])->findOrFail($this->invoiceId);

        $this->client_name = $this->invoice->client_name;
        $this->client_email = $this->invoice->client_email;
        $this->client_phone = $this->invoice->client_phone;
        $this->customer_address = $this->invoice->customer_address;
        $this->province = '';
        $this->canton = '';
        $this->distrito = '';
        $this->subtotal = $this->invoice->subtotal;
        $this->discount = $this->invoice->discount;
        $this->shipping = $this->invoice->shipping;
        $this->shipping_cost = $this->invoice->shipping_cost;
        $this->total = $this->invoice->total;
        $this->status = $this->invoice->status;
        $this->shipping_status = $this->invoice->shipping_status;
        $this->notes = $this->invoice->notes;
        $this->delivery_date = $this->invoice->delivery_date?->format('Y-m-d');
        $this->delivery_time_start = $this->invoice->delivery_time_start;
        $this->delivery_time_end = $this->invoice->delivery_time_end;
        $this->location = $this->invoice->location;
        $this->needs_bracelet_adjustment = $this->invoice->needs_bracelet_adjustment;
        $this->creation_date = $this->invoice->creation_date?->format('Y-m-d');
        $this->issued_date = $this->invoice->created_at?->format('Y-m-d');
        $this->estimated_utility = $this->invoice->estimated_utility;
        $this->cedula = $this->invoice->cedula;
    }

    public function save()
    {
        $this->validate();

        // Si se llenó provincia/cantón/distrito, se anexan a la dirección;
        // si están vacíos se conserva la dirección tal cual.
        $extras = array_filter([
            trim((string) $this->distrito) ?: null,
            trim((string) $this->canton) ?: null,
            trim((string) $this->province) ?: null,
        ]);
        $fullAddress = $extras
            ? implode(', ', array_filter([trim((string) $this->customer_address) ?: null, ...$extras]))
            : $this->customer_address;

        $this->invoice->update([
            'client_name' => $this->client_name,
            'client_email' => $this->client_email,
            'client_phone' => $this->client_phone,
            'customer_address' => $fullAddress,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'shipping' => $this->shipping,
            'shipping_cost' => $this->shipping_cost ?: null,
            'total' => $this->total,
            'status' => $this->status,
            'shipping_status' => $this->shipping_status,
            'notes' => $this->notes,
            'delivery_date' => $this->delivery_date ?: null,
            'delivery_time_start' => $this->delivery_time_start ?: null,
            'delivery_time_end' => $this->delivery_time_end ?: null,
            'location' => $this->location ?: null,
            'needs_bracelet_adjustment' => $this->needs_bracelet_adjustment ?? false,
            'creation_date' => $this->creation_date ?: null,
            'estimated_utility' => $this->estimated_utility ?: null,
            'cedula' => $this->cedula ?: null,
            'created_at' => $this->issued_date ? \Carbon\Carbon::parse($this->issued_date) : $this->invoice->created_at,
        ]);

        $this->loadInvoice();
        session()->flash('message', 'Factura actualizada.');
    }

    public function updateStatus($newStatus)
    {
        $this->invoice->update(['status' => $newStatus]);
        $this->status = $newStatus;
        $this->loadInvoice();
    }

    public function updateShippingStatus($newStatus)
    {
        $this->invoice->update(['shipping_status' => $newStatus]);
        $this->shipping_status = $newStatus;
        $this->loadInvoice();
    }

    public function deleteAbono($abonoId)
    {
        $abono = Abono::findOrFail($abonoId);
        $this->deleteR2File($abono->comprobante_path);
        $abono->delete();
        $this->loadInvoice();
        session()->flash('message', 'Abono eliminado.');
    }

    public function deleteReceipt($receiptId)
    {
        $receipt = InvoiceReceipt::where('invoice_id', $this->invoice->id)->findOrFail($receiptId);
        $this->deleteR2File($receipt->path);
        $receipt->delete();
        $this->loadInvoice();
        session()->flash('message', 'Comprobante eliminado.');
    }

    private function deleteR2File(?string $path): void
    {
        if (!$path) {
            return;
        }
        try {
            // En BD se guarda "/storage/..." pero la llave R2 es sin ese prefijo
            $key = preg_replace('#^/?storage/#', '', $path);
            if ($key && Storage::disk('r2')->exists($key)) {
                Storage::disk('r2')->delete($key);
            }
        } catch (\Throwable $e) {
            // No bloquear por un archivo que ya no existe
        }
    }

    public function delete()
    {
        foreach ($this->invoice->abonos as $abono) {
            $this->deleteR2File($abono->comprobante_path);
        }
        foreach ($this->invoice->receipts as $receipt) {
            $this->deleteR2File($receipt->path);
        }
        $this->invoice->items()->delete();
        $this->invoice->receipts()->delete();
        $this->invoice->abonos()->delete();
        $this->invoice->delete();

        session()->flash('message', 'Factura eliminada.');
        return redirect()->route('admin.invoices');
    }

    public function render()
    {
        return view('livewire.admin.invoice-detail')
            ->layout('components.admin-layout', ['title' => "Factura {$this->invoice->invoice_number}"]);
    }
}
