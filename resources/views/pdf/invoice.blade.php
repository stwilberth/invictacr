<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #0a0f1c; font-size: 24px; }
        .total { font-size: 18px; font-weight: bold; text-align: right; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invicta Costa Rica</h1>
        <p>Factura: {{ $invoice->invoice_number }}</p>
        <p>Fecha: {{ $invoice->created_at->format('d/m/Y') }}</p>
    </div>

    <h3>Cliente: {{ $invoice->client_name }}</h3>
    @if($invoice->client_email)<p>Email: {{ $invoice->client_email }}</p>@endif
    @if($invoice->client_phone)<p>Tel: {{ $invoice->client_phone }}</p>@endif

    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₡{{ number_format($item->unit_price, 0) }}</td>
                <td>₡{{ number_format($item->subtotal, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        @if($invoice->discount > 0)
            <p>Descuento: ₡{{ number_format($invoice->discount, 0) }}</p>
        @endif
        <p>Total: ₡{{ number_format($invoice->total, 0) }}</p>
    </div>
</body>
</html>