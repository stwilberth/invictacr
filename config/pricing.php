<?php

return [
    // Descuento del proveedor (variedadescr) sobre su precio público.
    // costo = precio_proveedor * (1 - supplier_discount_percent / 100)
    'supplier_discount_percent' => 40,

    // IVA aplicado a la venta (Invicta está en régimen general).
    'iva_percent' => 13,

    // Margen bruto mínimo sobre el ingreso neto (sin IVA).
    'minimum_margin_percent' => 25,

    // Competitividad: diferencia en colones sobre el precio del proveedor.
    'competitive_difference_amount' => 0,

    // Competitividad: diferencia porcentual sobre el precio del proveedor.
    'competitive_difference_percent' => 0,

    // Redondeo hacia arriba a múltiplos de este valor.
    'rounding' => 1000,

    // Margen mínimo en promociones (sobre ingreso neto).
    'promotion_minimum_margin_percent' => 25,
];
