<?php

namespace App\Livewire\Admin;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Livewire\Component;

class InvoiceCreate extends Component
{
    public $client_name = '';
    public $client_email = '';
    public $client_phone = '';
    public $customer_address = '';
    public $cedula = '';
    public $clientSearch = '';
    public $subtotal = 0;
    public $discount = 0;
    public $shipping = 0;
    public $shipping_cost = 0;
    public $total = 0;
    public $status = 'pending';
    public $shipping_status = 'pendiente';
    public $notes = '';
    public $creation_date = '';
    public $estimated_utility = 0;

    public $items = [];
    public $newItemName = '';
    public $newItemModel = '';
    public $newItemQuantity = 1;
    public $newItemPrice = 0;
    public $productSearch = '';

    protected function rules()
    {
        return [
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:255',
            'customer_address' => 'nullable|string',
            'cedula' => 'nullable|string|max:255',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'shipping' => 'required|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string',
            'shipping_status' => 'required|string',
            'notes' => 'nullable|string',
            'creation_date' => 'nullable|date',
            'estimated_utility' => 'nullable|numeric|min:0',
        ];
    }

    public function updatedClientName()
    {
        $this->clientSearch = $this->client_name;
    }

    public function selectClient($id)
    {
        $client = \App\Models\Client::findOrFail($id);
        $this->client_name = $client->name;
        $this->client_email = $client->email ?? '';
        $this->client_phone = $client->phone ?? '';
        $this->customer_address = $client->address ?? '';
        $this->clientSearch = '';
    }

    public function updatedNewItemModel()
    {
        $this->productSearch = $this->newItemModel;
    }

    public function selectProduct($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $this->newItemName = $product->title;
        $this->newItemModel = $product->modelo;
        $this->newItemPrice = $product->precio_venta;
        $this->productSearch = '';
    }

    public function addItem()
    {
        if (empty($this->newItemName) || $this->newItemPrice <= 0) {
            return;
        }

        $subtotalItem = $this->newItemQuantity * $this->newItemPrice;

        $this->items[] = [
            'product_name' => $this->newItemName,
            'product_model' => $this->newItemModel,
            'quantity' => $this->newItemQuantity,
            'unit_price' => $this->newItemPrice,
            'subtotal' => $subtotalItem,
        ];

        $this->newItemName = '';
        $this->newItemModel = '';
        $this->newItemQuantity = 1;
        $this->newItemPrice = 0;

        $this->recalculateTotal();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->recalculateTotal();
    }

    public function recalculateTotal()
    {
        $this->subtotal = 0;
        foreach ($this->items as $item) {
            $this->subtotal += $item['subtotal'];
        }
        $this->total = $this->subtotal - $this->discount + $this->shipping;
    }

    public function updatedDiscount()
    {
        $this->recalculateTotal();
    }

    public function updatedShipping()
    {
        $this->recalculateTotal();
    }

    public function save()
    {
        $this->validate();

        if (empty($this->items)) {
            session()->flash('error', 'Debe agregar al menos un producto.');
            return;
        }

        $invoiceNumber = $this->generateInvoiceNumber();

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'client_name' => $this->client_name,
            'client_email' => $this->client_email ?: null,
            'client_phone' => $this->client_phone ?: null,
            'customer_address' => $this->customer_address ?: null,
            'cedula' => $this->cedula ?: null,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'shipping' => $this->shipping,
            'shipping_cost' => $this->shipping_cost ?: null,
            'total' => $this->total,
            'status' => $this->status,
            'shipping_status' => $this->shipping_status,
            'notes' => $this->notes ?: null,
            'creation_date' => $this->creation_date ?: null,
            'estimated_utility' => $this->estimated_utility ?: null,
            'issued_at' => now(),
        ]);

        foreach ($this->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_name' => $item['product_name'],
                'product_model' => $item['product_model'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        return redirect()->route('admin.invoices.detail', $invoice->id)
            ->with('message', 'Factura creada exitosamente.');
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        $clientResults = [];
        if (strlen($this->clientSearch) >= 2) {
            $clientResults = \App\Models\Client::where('name', 'like', '%' . $this->clientSearch . '%')
                ->orWhere('email', 'like', '%' . $this->clientSearch . '%')
                ->orWhere('phone', 'like', '%' . $this->clientSearch . '%')
                ->limit(10)
                ->get();
        }
        $productResults = [];
        if (strlen($this->productSearch) >= 1) {
            $productResults = \App\Models\Product::where('modelo', 'like', '%' . $this->productSearch . '%')
                ->orWhere('title', 'like', '%' . $this->productSearch . '%')
                ->limit(10)
                ->get();
        }
        return view('livewire.admin.invoice-create', compact('clientResults', 'productResults'))
            ->layout('components.admin-layout', ['title' => 'Crear Factura']);
    }
}
