<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        "modelo",
        "title",
        "slug",
        "descripcion",
        "color",
        "brazalete",
        "coleccion",
        "tipo_movimiento",
        "size",
        "genero",
        "caja",
        "resistencia_agua",
        "video",
        "precio_venta",
        "precio_original",
        "descuento",
        "stock",
        "imagen",
        "isGif",
        "activo",
        "imagenes_extra",
        "caracteristicas",
        "vistas",
        "bloqueado",
        "proximo",
        "variedades_price",
        "variedades_increase",
    ];

    protected $casts = [
        "precio_venta" => "decimal:2",
        "precio_original" => "decimal:2",
        "descuento" => "integer",
        "stock" => "integer",
        "vistas" => "integer",
        "isGif" => "boolean",
        "activo" => "boolean",
        "bloqueado" => "boolean",
        "proximo" => "boolean",
        "variedades_price" => "integer",
        "variedades_increase" => "integer",
        "imagenes_extra" => "array",
        "caracteristicas" => "array",
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }

    public function getPriceAfterDiscountAttribute()
    {
        if ($this->descuento > 0) {
            return $this->precio_venta * (1 - $this->descuento / 100);
        }
        return $this->precio_venta;
    }

    public function getIsUpcomingAttribute()
    {
        return $this->proximo;
    }

    public function setBrazaleteAttribute($value)
    {
        $this->attributes['brazalete'] = static::normalizeBrazalete($value);
    }

    public static function normalizeBrazalete($value): ?string
    {
        return static::normalizeMaterial($value, true);
    }

    public function setCajaAttribute($value)
    {
        $this->attributes['caja'] = static::normalizeCaja($value);
    }

    public static function normalizeCaja($value): ?string
    {
        return static::normalizeMaterial($value, false);
    }

    private static function normalizeMaterial($value, bool $includeCuero): ?string
    {
        if (is_null($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);
        $lower = mb_strtolower($value);

        $map = [
            'acero inoxidable' => 'Acero Inoxidable',
            'stainless steel' => 'Acero Inoxidable',
            'silicona' => 'Silicona',
            'silicone' => 'Silicona',
            'rubber' => 'Silicona',
            'goma' => 'Silicona',
            'plastico' => 'Plastico',
            'plastic' => 'Plastico',
            'titanio' => 'Titanio',
            'titanium' => 'Titanio',
        ];

        if ($includeCuero) {
            $map['cuero'] = 'Cuero';
            $map['leather'] = 'Cuero';
        }

        foreach ($map as $search => $replacement) {
            if (str_contains($lower, $search)) {
                return $replacement;
            }
        }

        return $includeCuero ? 'Otros' : 'Acero Inoxidable';
    }

    public function setTipoMovimientoAttribute($value)
    {
        $this->attributes['tipo_movimiento'] = static::normalizeMovimiento($value);
    }

    public static function normalizeMovimiento($value): ?string
    {
        if (is_null($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);
        $lower = mb_strtolower($value);

        if (preg_match('/\bim-a[-]/i', $value) || str_contains($lower, 'automatic') || str_contains($lower, 'mecanico') || str_contains($lower, 'mechanical') || str_contains($lower, 'cuerda')) {
            return 'automatico';
        }

        if (preg_match('/\bim-q[-]/i', $value) || str_contains($lower, 'quartz') || str_contains($lower, 'cuarzo') || str_contains($lower, 'battery') || str_contains($lower, 'bateria') || str_contains($lower, 'pila') || str_contains($lower, 'solar')) {
            return 'cuarzo';
        }

        return 'cuarzo';
    }

    public function scopeRelatedTo($query, Product $product)
    {
        $currentPrice = (float) $product->precio_venta;
        $currentSize = $product->size ? (float) preg_replace('/[^0-9.]/', '', $product->size) : null;

        $query->where("activo", true)
            ->where("precio_venta", ">", 0)
            ->where("stock", ">", 0)
            ->where("id", "!=", $product->id);

        // Apply strict gender filter if the watch has a gender
        $gender = strtolower(trim($product->genero));
        if ($gender === 'hombre') {
            $query->whereIn('genero', ['hombre', 'unisex']);
        } elseif ($gender === 'mujer') {
            $query->whereIn('genero', ['mujer', 'unisex']);
        }

        // Build the scoring components
        $scoreSelects = [];
        $bindings = [];

        // Collection matching (Weight: 12)
        if ($product->coleccion) {
            $scoreSelects[] = "CASE WHEN coleccion = ? THEN 12 ELSE 0 END";
            $bindings[] = $product->coleccion;
        }

        // Gender preference matching (Weight: 5)
        if ($product->genero) {
            $scoreSelects[] = "CASE WHEN genero = ? THEN 5 ELSE 0 END";
            $bindings[] = $product->genero;
        }

        // Bracelet matching (Weight: 4)
        if ($product->brazalete) {
            $scoreSelects[] = "CASE WHEN brazalete = ? THEN 4 ELSE 0 END";
            $bindings[] = $product->brazalete;
        }

        // Color matching (Weight: 3)
        if ($product->color) {
            $scoreSelects[] = "CASE WHEN color = ? THEN 3 ELSE 0 END";
            $bindings[] = $product->color;
        }

        // Movement type matching (Weight: 3)
        if ($product->tipo_movimiento) {
            $scoreSelects[] = "CASE WHEN tipo_movimiento = ? THEN 3 ELSE 0 END";
            $bindings[] = $product->tipo_movimiento;
        }

        // Size matching (Weight: Up to 4)
        if ($currentSize) {
            $scoreSelects[] = "CASE 
                WHEN size IS NOT NULL AND size != '' THEN 
                    CASE 
                        WHEN ABS((size + 0) - ?) <= 2 THEN 4 
                        WHEN ABS((size + 0) - ?) <= 5 THEN 2 
                        ELSE 0 
                    END 
                ELSE 0 
            END";
            $bindings[] = $currentSize;
            $bindings[] = $currentSize;
        }

        // Price similarity deduction
        if ($currentPrice > 0) {
            $scoreSelects[] = "-1 * (ABS(precio_venta - ?) / ? * 6)";
            $bindings[] = $currentPrice;
            $bindings[] = $currentPrice;
        }

        // Add a tiny random shake to break ties and make it feel dynamic on page refreshes
        $scoreSelects[] = "(RAND() * 0.5)";

        $scoreExpression = count($scoreSelects) > 0 ? implode(" + ", $scoreSelects) : "1";

        return $query->selectRaw("*, ($scoreExpression) as similarity_score", $bindings)
            ->orderByRaw("similarity_score DESC");
    }
}

