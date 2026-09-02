<?php

namespace Tests\Unit;

use App\Services\PricingService;
use Tests\TestCase;

class PricingServiceTest extends TestCase
{
    private PricingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PricingService::class);
    }

    public function test_costo_refleja_descuento_del_40_sin_iva()
    {
        $this->assertEquals('60000.00', $this->service->calculate(100000)['precio_costo']);
    }

    public function test_costo_de_50000()
    {
        $this->assertEquals('30000.00', $this->service->calculate(50000)['precio_costo']);
    }

    public function test_margen_bruto_pct_sobre_neto()
    {
        $pct = $this->service->margenBrutoPct(100000, 60000);
        $this->assertEqualsWithDelta(32.2, $pct, 0.1);
    }

    public function test_precio_minimo_60000_margen_25()
    {
        $this->assertEquals('90400.00', $this->service->precioMinimo(60000));
    }

    public function test_precio_final_referencia_100000()
    {
        $this->assertEquals('100000', $this->service->calculate(100000)['precio_final']);
    }

    public function test_redondeo_nunca_rompe_margen_minimo()
    {
        $costo = $this->service->calculate(100000)['precio_costo'];
        $final = $this->service->calculate(100000)['precio_final'];

        $this->assertGreaterThanOrEqual(
            config('pricing.minimum_margin_percent'),
            $this->service->margenBrutoPct($final, $costo),
        );
        $this->assertSame(0, (int) $final % (int) config('pricing.rounding'));
    }

    public function test_precio_objetivo_por_monto()
    {
        config(['pricing.competitive_difference_amount' => -2000]);
        config(['pricing.competitive_difference_percent' => 0]);

        $this->assertEquals('98000.00', $this->service->precioObjetivo(100000));
    }

    public function test_precio_objetivo_por_porcentaje()
    {
        config(['pricing.competitive_difference_amount' => 0]);
        config(['pricing.competitive_difference_percent' => -2]);

        $this->assertEquals('98000.00', $this->service->precioObjetivo(100000));
    }

    public function test_precio_base_respeta_minimo()
    {
        config(['pricing.competitive_difference_amount' => -15000]);
        config(['pricing.competitive_difference_percent' => 0]);

        $objetivo = $this->service->precioObjetivo(100000);
        $minimo = $this->service->precioMinimo(60000);

        $this->assertEquals('85000.00', $objetivo);
        $this->assertEquals($minimo, $this->service->precioBase($objetivo, $minimo));
    }

    public function test_promocion_9_por_ciento_valida()
    {
        $this->assertTrue($this->service->descuentoValido(100000, 60000, 9));
    }

    public function test_promocion_10_por_ciento_rechazada()
    {
        $this->assertFalse($this->service->descuentoValido(100000, 60000, 10));
    }

    public function test_descuento_maximo_referencia()
    {
        $this->assertEqualsWithDelta(9.6, $this->service->descuentoMaximo(100000, 60000), 0.1);
    }

    public function test_precision_sin_errores_de_punto_flotante()
    {
        $resultado = $this->service->calculate(99999);

        $this->assertSame('99999.00', $resultado['precio_original']);
        $this->assertSame('59999.40', $resultado['precio_costo']);
        $this->assertGreaterThanOrEqual(
            config('pricing.minimum_margin_percent'),
            $resultado['margen_bruto_pct'],
        );
    }

    public function test_precio_invalido_lanza_excepcion()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->service->calculate(0);
    }
}
