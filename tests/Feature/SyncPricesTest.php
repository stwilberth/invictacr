<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncPricesTest extends TestCase
{
    use RefreshDatabase;

    protected function makeProduct(array $overrides = []): Product
    {
        return Product::create(array_merge([
            'modelo' => '50000',
            'title' => 'Reloj Invicta Prueba',
            'slug' => 'invicta-prueba',
            'precio_original' => 100000,
            'precio_costo' => 60000,
            'precio_venta' => 0,
            'descuento' => 0,
            'stock' => 5,
            'activo' => true,
            'bloqueado' => false,
            'proximo' => false,
            'manual_override' => false,
        ], $overrides));
    }

    public function test_recalcula_precio_de_producto_elegible()
    {
        $product = $this->makeProduct(['precio_venta' => 0]);

        $this->artisan('invicta:sync-prices')->assertExitCode(0);

        $product->refresh();

        $this->assertEquals(60000.00, (float) $product->precio_costo);
        $this->assertEquals(100000.00, (float) $product->precio_venta);
    }

    public function test_excluye_producto_sin_costo()
    {
        $product = $this->makeProduct(['precio_costo' => null, 'precio_venta' => 0]);

        $this->artisan('invicta:sync-prices')->assertExitCode(0);

        $product->refresh();

        $this->assertNull($product->precio_costo);
        $this->assertEquals(0.00, (float) $product->precio_venta);
    }

    public function test_excluye_manual_override()
    {
        $product = $this->makeProduct(['manual_override' => true, 'precio_venta' => 75000]);

        $this->artisan('invicta:sync-prices')->assertExitCode(0);

        $product->refresh();

        $this->assertEquals(75000.00, (float) $product->precio_venta);
        $this->assertEquals(60000.00, (float) $product->precio_costo);
    }

    public function test_force_recalcula_manual_override()
    {
        $product = $this->makeProduct(['manual_override' => true, 'precio_venta' => 75000]);

        $this->artisan('invicta:sync-prices', ['--force' => true])->assertExitCode(0);

        $product->refresh();

        $this->assertEquals(60000.00, (float) $product->precio_costo);
        $this->assertEquals(100000.00, (float) $product->precio_venta);
    }

    public function test_excluye_bloqueado_y_proximo()
    {
        $bloqueado = $this->makeProduct(['modelo' => '50001', 'slug' => 'invicta-50001', 'bloqueado' => true]);
        $proximo = $this->makeProduct(['modelo' => '50002', 'slug' => 'invicta-50002', 'proximo' => true]);

        $this->artisan('invicta:sync-prices')->assertExitCode(0);

        $bloqueado->refresh();
        $proximo->refresh();

        $this->assertEquals(0.00, (float) $bloqueado->precio_venta);
        $this->assertEquals(0.00, (float) $proximo->precio_venta);
    }

    public function test_dry_run_no_modifica_bd()
    {
        $product = $this->makeProduct(['precio_venta' => 0]);

        $this->artisan('invicta:sync-prices', ['--dry-run' => true])
            ->expectsOutputToContain('MODO DRY-RUN')
            ->assertExitCode(0);

        $product->refresh();

        $this->assertEquals(0.00, (float) $product->precio_venta);
    }

    public function test_idempotente_no_hace_update_si_no_cambia()
    {
        $product = $this->makeProduct(['precio_venta' => 100000, 'precio_costo' => 60000]);

        $this->artisan('invicta:sync-prices')->assertExitCode(0);

        $product->refresh();

        $this->assertEquals(60000.00, (float) $product->precio_costo);
        $this->assertEquals(100000.00, (float) $product->precio_venta);
    }
}
