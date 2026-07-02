<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            "modelo" => $this->faker->unique()->numerify("######"),
            "title" => $this->faker->word,
            "slug" => $this->faker->slug,
            "descripcion" => $this->faker->sentence,
            "color" => $this->faker->randomElement(["Plateado", "Dorado", "Negro", "Azul"]),
            "brazalete" => $this->faker->randomElement(config('brazaletes', ['Acero Inoxidable', 'Silicona', 'Cuero'])),
            "coleccion" => $this->faker->randomElement(["Pro Diver", "Reserve", "Venom", "Angel"]),
            "tipo_movimiento" => $this->faker->randomElement(["Cuarzo", "Automático"]),
            "genero" => $this->faker->randomElement(["hombre", "mujer", "unisex"]),
            "caja" => "Acero Inoxidable",
            "resistencia_agua" => (string) $this->faker->randomElement([50, 100, 200]),
            "precio_venta" => $this->faker->randomFloat(2, 50, 500),
            "stock" => $this->faker->numberBetween(1, 20),
            "imagen" => $this->faker->imageUrl(),
            "activo" => true,
        ];
    }
}
