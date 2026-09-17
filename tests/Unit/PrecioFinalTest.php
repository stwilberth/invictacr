<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class PrecioFinalTest extends TestCase
{
    public function test_aplica_13_y_no_redondea_si_ya_es_multiplo_de_500()
    {
        // 100000 * 1.13 = 113000 (múltiplo de 500).
        $this->assertSame(113000, Product::precioConIva(100000));
    }

    public function test_redondea_hacia_arriba_a_multiplos_de_500()
    {
        // 65000 * 1.13 = 73450 -> 73500.
        $this->assertSame(73500, Product::precioConIva(65000));
    }

    public function test_redondea_cualquier_fraccion_hacia_arriba()
    {
        // 10000 * 1.13 = 11300 -> 11500.
        $this->assertSame(11500, Product::precioConIva(10000));
    }

    public function test_multiplo_exacto_no_sube_al_siguiente()
    {
        // 50000 * 1.13 = 56500 exacto -> se queda.
        $this->assertSame(56500, Product::precioConIva(50000));
    }

    public function test_montos_cero_o_negativos_retornan_cero()
    {
        $this->assertSame(0, Product::precioConIva(0));
        $this->assertSame(0, Product::precioConIva(-100));
        $this->assertSame(0, Product::precioConIva(''));
    }

    public function test_precio_final_usa_base_con_descuento()
    {
        $product = new Product(['precio_venta' => 100000, 'descuento' => 10]);
        // Base con descuento: 90000 * 1.13 = 101700 -> 102000.
        $this->assertSame(102000, $product->precio_final);
    }

    public function test_precio_final_sin_descuento()
    {
        $product = new Product(['precio_venta' => 65000, 'descuento' => 0]);
        $this->assertSame(73500, $product->precio_final);
    }

    public function test_precio_final_siempre_multiplo_de_500()
    {
        foreach ([9999, 12345, 77777, 250, 1, 99999.99] as $monto) {
            $final = Product::precioConIva($monto);
            $this->assertSame(0, $final % 500, "Falla para monto {$monto}");
            $this->assertGreaterThanOrEqual($monto * 1.13, $final);
        }
    }
}
