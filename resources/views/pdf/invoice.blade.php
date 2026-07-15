<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo {{ $invoice->client_name }}</title>
    <style>
        * { margin: 0; padding: 0; }
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        table { border-collapse: collapse; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    {{-- ==================== PÁGINA 1: RECIBO ==================== --}}
    <div style="position: relative; min-height: 780px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td width="60%" align="center" valign="top" style="padding: 20px 10px 10px 10px;">
                @if(file_exists(public_path('logo.png')))
                    <img src="{{ public_path('logo.png') }}" width="70" alt="Invicta" /><br/>
                @endif
                <span style="font-size: 22px; font-weight: bold; color: #00b4d8;">invictaCR.com</span><br/>
                <span style="font-size: 9px; color: #555;">invictaCR.com | invictacostarica.com</span><br/>
                <span style="font-size: 9px; color: #555;">Tel: 8671-1422</span><br/>
                <span style="font-size: 9px; color: #555;">San José, Costa Rica</span>
            </td>
            <td width="40%" align="right" valign="top" style="padding: 20px 10px 10px 10px;">
                <span style="font-size: 14px; font-weight: bold; color: #333;">Recibo # {{ $invoice->invoice_number }}</span><br/>
                <span style="font-size: 9px; color: #555;">Fecha: {{ $invoice->created_at->format('d/m/Y') }}</span><br/>
                <span style="font-size: 9px; color: #555;">Estado: {{ ucfirst($invoice->status) }}</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td style="border-top: 1px solid #ccc; font-size: 1px; line-height: 1px;">&nbsp;</td></tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding: 10px;">
                <span style="font-size: 12px; font-weight: bold; color: #333;">Cliente:</span><br/>
                <span style="font-size: 10px; color: #555;">{{ $invoice->client_name }}</span><br/>
                @if($invoice->client_phone)
                    <span style="font-size: 10px; color: #555;">Tel: {{ $invoice->client_phone }}</span>
                @endif
            </td>
        </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 5px;">
        <thead>
            <tr>
                <th width="35%" align="left" style="background-color: #00b4d8; color: white; padding: 6px 8px; font-size: 9px;">Producto</th>
                <th width="25%" align="left" style="background-color: #00b4d8; color: white; padding: 6px 8px; font-size: 9px;">Modelo</th>
                <th width="10%" align="center" style="background-color: #00b4d8; color: white; padding: 6px 8px; font-size: 9px;">Cant</th>
                <th width="15%" align="right" style="background-color: #00b4d8; color: white; padding: 6px 8px; font-size: 9px;">Precio</th>
                <th width="15%" align="right" style="background-color: #00b4d8; color: white; padding: 6px 8px; font-size: 9px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $key => $item)
            <tr style="background-color: {{ $key % 2 == 0 ? '#fff' : '#f9f9f9' }};">
                <td style="padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 9px;">{{ $item->product_name }}</td>
                <td style="padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 9px;">{{ $item->product_model ?? '-' }}</td>
                <td align="center" style="padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 9px;">{{ $item->quantity }}</td>
                <td align="right" style="padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 9px;">CRC {{ number_format($item->unit_price, 0) }}</td>
                <td align="right" style="padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 9px;">CRC {{ number_format($item->subtotal, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td width="60%">&nbsp;</td>
            <td width="40%" style="padding: 10px;" align="right">
                <span style="font-size: 10px; color: #555;">Subtotal: CRC {{ number_format($invoice->subtotal, 0) }}</span><br/>
                @if($invoice->discount > 0)
                    <span style="font-size: 10px; color: #555;">Descuento: -CRC {{ number_format($invoice->discount, 0) }}</span><br/>
                @endif
                @if($invoice->shipping > 0)
                    <span style="font-size: 10px; color: #555;">Envío: CRC {{ number_format($invoice->shipping, 0) }}</span><br/>
                @endif
                <span style="font-size: 14px; font-weight: bold; color: #333;">Total: CRC {{ number_format($invoice->total, 0) }}</span>
            </td>
        </tr>
    </table>

    @if($invoice->abonos->count() > 0)
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding: 10px;">
                <span style="font-size: 12px; font-weight: bold; color: #333;">Abonos:</span>
            </td>
        </tr>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th width="40%" align="left" style="background-color: #00b4d8; color: white; padding: 5px 8px; font-size: 9px;">Fecha</th>
                <th width="30%" align="left" style="background-color: #00b4d8; color: white; padding: 5px 8px; font-size: 9px;">Monto</th>
                <th width="30%" align="left" style="background-color: #00b4d8; color: white; padding: 5px 8px; font-size: 9px;">Nota</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->abonos as $abono)
            <tr>
                <td style="padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 9px;">{{ $abono->date ? $abono->date->format('d/m/Y H:i') : '-' }}</td>
                <td style="padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 9px;">CRC {{ number_format($abono->amount, 0) }}</td>
                <td style="padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 9px;">{{ $abono->note ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @php
        $totalAbonado = $invoice->abonos->sum('amount');
        $saldo = $invoice->total - $totalAbonado;
    @endphp
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td width="60%">&nbsp;</td>
            <td width="40%" align="right" style="padding: 5px 10px;">
                <span style="font-size: 10px; color: #555; font-weight: bold;">Total Abonado: CRC {{ number_format($totalAbonado, 0) }}</span><br/>
                @if($saldo > 0)
                    <span style="font-size: 10px; color: #e63946; font-weight: bold;">Saldo Pendiente: CRC {{ number_format($saldo, 0) }}</span>
                @else
                    <span style="font-size: 10px; color: #2a9d8f; font-weight: bold;">Pagado</span>
                @endif
            </td>
        </tr>
    </table>
    @endif
    </div>

    <div style="position: absolute; bottom: 15px; left: 0; width: 100%; text-align: center; padding: 0 40px;">
        <span style="font-size: 9px; color: #999;">Este documento es su comprobante de compra y garantía. Guárdelo para reclamaciones.</span>
    </div>

    {{-- ==================== PÁGINA 2: TÉRMINOS DE GARANTÍA ==================== --}}
    <div class="page-break" style="padding: 30px 40px;">

        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center" style="padding: 10px 0 20px 0;">
                    @if(file_exists(public_path('logo.png')))
                        <img src="{{ public_path('logo.png') }}" width="50" alt="Invicta" /><br/>
                    @endif
                    <span style="font-size: 20px; font-weight: bold; color: #00b4d8;">invictaCR.com</span>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
            <tr><td style="padding: 0 0 20px 0;"><span style="font-size: 18px; font-weight: bold; color: #333;">Garantía Real 6 Meses</span></td></tr>
            <tr><td style="padding: 0 0 5px 0;"><span style="font-size: 11px; color: #555;">Tu inversión está protegida. Ofrecemos un respaldo directo y transparente para que solo te preocupes por lucir tu nuevo Invicta.</span></td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
            <tr><td style="border-top: 1px solid #ccc; font-size: 1px; line-height: 1px;">&nbsp;</td></tr>
        </table>

        {{-- ¿Qué cubrimos? --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
            <tr>
                <td style="padding: 0 0 10px 0;">
                    <span style="font-size: 14px; font-weight: bold; color: #333;">¿Qué Cubrimos?</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">
                    <span style="color: #00b4d8;">&#10003;</span>
                    <span style="font-size: 11px; color: #555;"> Defectos de fabricación en materiales y mano de obra.</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">
                    <span style="color: #00b4d8;">&#10003;</span>
                    <span style="font-size: 11px; color: #555;"> Componentes internos: Movimiento del reloj, manecillas, carátula y marcadores.</span>
                </td>
            </tr>
        </table>

        {{-- Período de validez --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
            <tr>
                <td style="padding: 12px; background-color: #f0f9ff; border: 1px solid #bae6fd;">
                    <span style="font-size: 12px; font-weight: bold; color: #0369a1;">Período de Validez</span><br/>
                    <span style="font-size: 11px; color: #555;">Su reloj está respaldado por una garantía limitada de <strong>6 meses</strong> a partir de la fecha de entrega original.</span>
                </td>
            </tr>
        </table>

        {{-- Excepciones --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
            <tr>
                <td style="padding: 0 0 10px 0;">
                    <span style="font-size: 14px; font-weight: bold; color: #333;">Excepciones</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">
                    <span style="color: #dc2626;">&#10007;</span>
                    <span style="font-size: 11px; color: #555;"> Cristal, corona, correa, brazalete y batería.</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">
                    <span style="color: #dc2626;">&#10007;</span>
                    <span style="font-size: 11px; color: #555;"> Daños por uso indebido, accidentes o desgaste normal.</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">
                    <span style="color: #dc2626;">&#10007;</span>
                    <span style="font-size: 11px; color: #555;"> Daños por entrada de agua (humedad) por negligencia.</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">
                    <span style="color: #dc2626;">&#10007;</span>
                    <span style="font-size: 11px; color: #555;"> Reparaciones en centros NO autorizados (incluye cambio de batería).</span>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
            <tr><td style="border-top: 1px solid #ccc; font-size: 1px; line-height: 1px;">&nbsp;</td></tr>
        </table>

        {{-- Términos --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
            <tr>
                <td width="33%" valign="top" style="padding-right: 15px;">
                    <span style="font-size: 12px; font-weight: bold; color: #333;">Comprobante</span><br/>
                    <span style="font-size: 11px; color: #555;">Deberá presentar el documento de compra PDF que contiene su número de factura único.</span>
                </td>
                <td width="33%" valign="top" style="padding-right: 15px;">
                    <span style="font-size: 12px; font-weight: bold; color: #333;">Proceso de Envío</span><br/>
                    <span style="font-size: 11px; color: #555;">El cliente asume los gastos de envío para evaluación. El único medio autorizado es Correos de Costa Rica.</span>
                </td>
                <td width="33%" valign="top">
                    <span style="font-size: 12px; font-weight: bold; color: #333;">Compromiso Real</span><br/>
                    <span style="font-size: 11px; color: #555;">Si no es posible reparar la pieza, le entregaremos un reloj nuevo igual o de valor equivalente.</span>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 25px;">
            <tr><td style="border-top: 1px solid #ccc; font-size: 1px; line-height: 1px;">&nbsp;</td></tr>
        </table>

        {{-- Aviso --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 15px;">
            <tr>
                <td style="padding: 10px; background-color: #fffbeb; border: 1px solid #fde68a;">
                    <span style="font-size: 11px; color: #92400e; font-weight: bold;">Al comprar un reloj usted acepta los términos y condiciones de esta garantía limitada. No incluya cajas de regalo ni correas especiales en caso de envío para evaluación.</span>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
            <tr>
                <td align="center">
                    <span style="font-size: 9px; color: #999;">Última actualización: 16 de marzo de 2026</span>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>
