<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Services\SearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Product::create([
            "modelo" => "12345",
            "title" => "Reloj Invicta Pro Diver",
            "slug" => "reloj-invicta-pro-diver",
            "genero" => "hombre",
            "coleccion" => "Pro Diver",
            "color" => "Azul",
            "brazalete" => "Acero Inoxidable",
            "tipo_movimiento" => "Cuarzo",
            "caja" => "Acero Inoxidable",
            "resistencia_agua" => "200",
            "precio_venta" => 150,
            "stock" => 5,
            "activo" => true,
        ]);

        Product::create([
            "modelo" => "67890",
            "title" => "Reloj Invicta Mujer",
            "slug" => "reloj-invicta-mujer",
            "genero" => "mujer",
            "coleccion" => "Angel",
            "color" => "Dorado",
            "brazalete" => "Silicona",
            "tipo_movimiento" => "Cuarzo",
            "caja" => "Acero Inoxidable",
            "resistencia_agua" => "100",
            "precio_venta" => 200,
            "stock" => 3,
            "activo" => true,
        ]);

        Product::create([
            "modelo" => "11111",
            "title" => "Reloj Invicta Automatico",
            "slug" => "reloj-invicta-automatico",
            "genero" => "hombre",
            "coleccion" => "Venom",
            "color" => "Negro",
            "brazalete" => "Cuero",
            "tipo_movimiento" => "Automático",
            "caja" => "Acero Inoxidable",
            "resistencia_agua" => "50m",
            "precio_venta" => 300,
            "stock" => 2,
            "activo" => true,
        ]);
    }

    // ─── SearchService parse() ───────────────────────────────────

    public function test_parse_coleccion()
    {
        $service = app(SearchService::class);
        $result = $service->parse("Pro Diver");
        $this->assertEquals("pro diver", $result["coleccion"] ?? null);
        $this->assertArrayNotHasKey("q", $result);
    }

    public function test_parse_genero()
    {
        $service = app(SearchService::class);
        $result = $service->parse("mujer");
        $this->assertEquals("mujer", $result["gender"] ?? null);
        $this->assertArrayNotHasKey("q", $result);
    }

    public function test_parse_color()
    {
        $service = app(SearchService::class);
        $result = $service->parse("Dorado");
        $this->assertEquals("dorado", $result["color"] ?? null);
    }

    public function test_parse_brazalete()
    {
        $service = app(SearchService::class);
        $result = $service->parse("silicona");
        $this->assertEquals("silicona", $result["brazalete"] ?? null);
    }

    public function test_parse_tipo_movimiento()
    {
        $service = app(SearchService::class);
        $result = $service->parse("automatico");
        $this->assertEquals("automatico", $result["tipo_movimiento"] ?? null);
    }

    public function test_parse_resistencia_agua()
    {
        $service = app(SearchService::class);
        $result = $service->parse("200m");
        $this->assertEquals("200", $result["resistencia_agua"] ?? null);
    }

    public function test_parse_resistencia_agua_within_50()
    {
        $service = app(SearchService::class);
        $result = $service->parse("180");
        $this->assertEquals("200", $result["resistencia_agua"] ?? null);
    }

    public function test_parse_unmatched_words_become_q()
    {
        $service = app(SearchService::class);
        $result = $service->parse("speedway azul");
        $this->assertArrayHasKey("q", $result);
    }

    public function test_parse_generic_words_filtered()
    {
        $service = app(SearchService::class);
        $result = $service->parse("reloj invicta pro diver");
        $this->assertEquals("pro diver", $result["coleccion"] ?? null);
        $this->assertArrayNotHasKey("q", $result);
    }

    // ─── Controller index() ──────────────────────────────────────

    public function test_index_with_gender_param()
    {
        $response = $this->get("/relojes?gender=mujer");
        $response->assertStatus(200);
        $response->assertSee("67890");
        $response->assertDontSee("11111");
    }

    public function test_index_with_coleccion_param()
    {
        $response = $this->get("/relojes?coleccion=Pro+Diver");
        $response->assertStatus(200);
        $response->assertSee("12345");
        $response->assertDontSee("67890");
    }

    public function test_index_with_old_genero_param()
    {
        $response = $this->get("/relojes?genero=mujer");
        $response->assertStatus(200);
        $response->assertSee("67890");
    }

    public function test_index_no_params_shows_all()
    {
        $response = $this->get("/relojes");
        $response->assertStatus(200);
        $response->assertSee("12345");
        $response->assertSee("67890");
        $response->assertSee("11111");
    }

}
