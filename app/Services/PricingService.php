<?php

namespace App\Services;

use InvalidArgumentException;

/**
 * Única fuente de verdad para el cálculo de precios.
 *
 * Reglas de negocio:
 *  - precio_costo se calcula sin IVA (variedadescr es régimen simplificado).
 *  - precio_venta y precio promocional llevan IVA (Invicta régimen general).
 *  - Nunca se vende (ni en promoción) por debajo del margen mínimo.
 *  - Los precios finales se redondean hacia arriba a múltiplos de config('pricing.rounding').
 *
 * Todo el cálculo usa BCMath para evitar errores de punto flotante.
 */
class PricingService
{
    private const SCALE = 6;

    /**
     * Calcula la estructura completa de precios para un precio de proveedor P.
     *
     * @param  int|float|string  $precioOriginal  Precio público del proveedor (sin IVA).
     * @return array<string, mixed>
     *
     * @throws InvalidArgumentException si $precioOriginal <= 0.
     */
    public function calculate(int|float|string $precioOriginal): array
    {
        $p = $this->normalize($precioOriginal);
        if ($p === null || $this->bc($p) <= 0) {
            throw new InvalidArgumentException('El precio del proveedor debe ser mayor a 0.');
        }

        $costo = $this->costo($p);
        $minimo = $this->precioMinimo($costo);
        $objetivo = $this->precioObjetivo($p);
        $base = $this->precioBase($objetivo, $minimo);
        $final = $this->precioFinal($costo, $base);

        $ingresoNeto = $this->ingresoNeto($final);
        $margen = $this->margenBruto($final, $costo);
        $margenPct = $this->margenBrutoPct($final, $costo);

        return [
            'precio_original' => $this->money($p),
            'precio_costo' => $this->money($costo),
            'precio_minimo' => $this->money($minimo),
            'precio_objetivo' => $this->money($objetivo),
            'precio_base' => $this->money($base),
            'precio_final' => $final,
            'ingreso_neto' => $this->money($ingresoNeto),
            'margen_bruto' => $this->money($margen),
            'margen_bruto_pct' => (float) $margenPct,
            'descuento_maximo' => $this->descuentoMaximo($final, $costo),
        ];
    }

    /**
     * costo = P × (1 - D/100)  (sin IVA).
     */
    public function costo(int|float|string $precioOriginal): string
    {
        $p = $this->normalize($precioOriginal);
        $d = $this->cfg('supplier_discount_percent', 40);

        return bcsub($p, bcdiv(bcmul($p, (string) $d, self::SCALE), '100', self::SCALE), self::SCALE);
    }

    /**
     * ingreso neto (sin IVA) = precioVenta / (1 + IVA/100).
     */
    public function ingresoNeto(int|float|string $precioVenta): string
    {
        $v = $this->normalize($precioVenta);
        $iva = $this->cfg('iva_percent', 13);
        $factor = bcadd('1', bcdiv((string) $iva, '100', self::SCALE), self::SCALE);

        return bcdiv($v, $factor, self::SCALE);
    }

    /**
     * margen bruto (colones) = ingresoNeto - costo.
     */
    public function margenBruto(int|float|string $precioVenta, int|float|string $precioCosto): string
    {
        return bcsub($this->ingresoNeto($precioVenta), $this->normalize($precioCosto), self::SCALE);
    }

    /**
     * margen bruto (%) sobre ingreso neto.
     */
    public function margenBrutoPct(int|float|string $precioVenta, int|float|string $precioCosto): float
    {
        $ingreso = $this->ingresoNeto($precioVenta);
        if ($this->bc($ingreso) <= 0) {
            return 0.0;
        }

        $pct = bcdiv(
            bcmul($this->margenBruto($precioVenta, $precioCosto), '100', self::SCALE),
            $ingreso,
            self::SCALE,
        );

        return (float) $pct;
    }

    /**
     * precio mínimo rentable (con IVA) = (costo / (1 - M/100)) × (1 + IVA/100).
     */
    public function precioMinimo(int|float|string $precioCosto): string
    {
        $costo = $this->normalize($precioCosto);
        $m = $this->cfg('minimum_margin_percent', 25);
        $iva = $this->cfg('iva_percent', 13);

        $denominador = bcsub('1', bcdiv((string) $m, '100', self::SCALE), self::SCALE);
        $baseNeto = bcdiv($costo, $denominador, self::SCALE);
        $factorIva = bcadd('1', bcdiv((string) $iva, '100', self::SCALE), self::SCALE);

        return bcmul($baseNeto, $factorIva, self::SCALE);
    }

    /**
     * precio objetivo/competitivo = P × (1 + pct/100) + amount.
     */
    public function precioObjetivo(int|float|string $precioOriginal): string
    {
        $p = $this->normalize($precioOriginal);
        $pct = $this->cfg('competitive_difference_percent', 0);
        $amount = $this->cfg('competitive_difference_amount', 0);

        $objetivo = bcadd(
            bcmul($p, bcadd('1', bcdiv((string) $pct, '100', self::SCALE), self::SCALE), self::SCALE),
            (string) $amount,
            self::SCALE,
        );

        return $objetivo;
    }

    /**
     * precio base = max(precioObjetivo, precioMinimo).
     */
    public function precioBase(int|float|string $precioObjetivo, int|float|string $precioMinimo): string
    {
        $objetivo = $this->normalize($precioObjetivo);
        $minimo = $this->normalize($precioMinimo);

        return bccomp($objetivo, $minimo, self::SCALE) >= 0 ? $objetivo : $minimo;
    }

    /**
     * precio final (con IVA) redondeado hacia arriba a múltiplos de rounding,
     * garantizando que el redondeo nunca deje el margen por debajo del mínimo.
     *
     * @return string Entero (múltiplo de rounding).
     */
    public function precioFinal(int|float|string $precioCosto, int|float|string $precioBase): string
    {
        $costo = $this->normalize($precioCosto);
        $base = $this->normalize($precioBase);
        $rounding = $this->cfg('rounding', 1000);
        $m = $this->cfg('minimum_margin_percent', 25);

        $final = $this->roundUpTo($base, $rounding);

        $guard = 0;
        while ($this->margenBrutoPct($final, $costo) < $m && $guard < 100000) {
            $final = bcadd($final, (string) $rounding, 0);
            $guard++;
        }

        return $final;
    }

    /**
     * precio promocional = precioVenta × (1 - descuento/100).
     */
    public function precioPromocional(int|float|string $precioVenta, int $descuento): string
    {
        $v = $this->normalize($precioVenta);
        $d = max(0, min(100, (int) $descuento));

        return bcmul($v, bcsub('1', bcdiv((string) $d, '100', self::SCALE), self::SCALE), self::SCALE);
    }

    /**
     * margen promocional (%) sobre ingreso neto de la promoción.
     */
    public function margenPromocionalPct(int|float|string $precioPromocional, int|float|string $precioCosto): float
    {
        return $this->margenBrutoPct($precioPromocional, $precioCosto);
    }

    /**
     * Descuento máximo permitido (%) sin romper el margen mínimo de promoción.
     */
    public function descuentoMaximo(int|float|string $precioVenta, int|float|string $precioCosto): float
    {
        $v = $this->normalize($precioVenta);
        $costo = $this->normalize($precioCosto);
        if ($this->bc($v) <= 0) {
            return 0.0;
        }

        $mProm = $this->cfg('promotion_minimum_margin_percent', 25);
        $iva = $this->cfg('iva_percent', 13);

        $denominador = bcsub('1', bcdiv((string) $mProm, '100', self::SCALE), self::SCALE);
        $baseNeto = bcdiv($costo, $denominador, self::SCALE);
        $factorIva = bcadd('1', bcdiv((string) $iva, '100', self::SCALE), self::SCALE);
        $promMin = bcmul($baseNeto, $factorIva, self::SCALE);

        $pct = bcmul(
            bcsub('1', bcdiv($promMin, $v, self::SCALE), self::SCALE),
            '100',
            self::SCALE,
        );

        return max(0.0, (float) $pct);
    }

    /**
     * Indica si un descuento promocional respeta el margen mínimo de promoción.
     */
    public function descuentoValido(int|float|string $precioVenta, int|float|string $precioCosto, int $descuento): bool
    {
        $promo = $this->precioPromocional($precioVenta, $descuento);
        $mProm = $this->cfg('promotion_minimum_margin_percent', 25);

        return $this->margenPromocionalPct($promo, $precioCosto) >= $mProm;
    }

    private function roundUpTo(string $amount, int $rounding): string
    {
        if ($rounding <= 0) {
            $rounding = 1000;
        }

        $div = bcdiv($amount, (string) $rounding, self::SCALE);

        return bcmul($this->bcCeil($div), (string) $rounding, 0);
    }

    private function bcCeil(string $num): string
    {
        if (bccomp($num, '0', self::SCALE) <= 0) {
            return '0';
        }

        $floor = bcadd($num, '0', 0);
        if (bccomp($num, $floor, self::SCALE) > 0) {
            return bcadd($floor, '1', 0);
        }

        return $floor;
    }

    private function cfg(string $key, int $default): int
    {
        return (int) config("pricing.{$key}", $default);
    }

    private function normalize(int|float|string $value): string
    {
        $value = trim((string) $value);
        if ($value === '' || !is_numeric($value)) {
            return '0';
        }

        return $value;
    }

    private function money(string $value): string
    {
        return bcadd($value, '0', 2);
    }

    private function bc(string $value): float
    {
        return (float) $value;
    }
}
