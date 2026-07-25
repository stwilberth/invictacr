<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        "disponibilidad",
        "imagen",
        "activo",
        "caracteristicas",
        "vistas",
        "bloqueado",
        "proximo",
    ];

    protected $casts = [
        "precio_venta" => "decimal:2",
        "precio_original" => "decimal:2",
        "descuento" => "integer",
        "stock" => "integer",
        "vistas" => "integer",
        "activo" => "boolean",
        "bloqueado" => "boolean",
        "proximo" => "boolean",
        "caracteristicas" => "array",
    ];

    public function getImagenAttribute($value)
    {
        if (!$value) {
            return $value;
        }

        if (str_starts_with($value, '/storage/')) {
            $path = str_replace('/storage/', '', $value);
            return 'https://cdn.invictacostarica.com/' . $path;
        }

        return $value;
    }

    public function setImagenAttribute($value)
    {
        if ($value && str_starts_with($value, 'https://cdn.invictacostarica.com')) {
            $value = str_replace('https://cdn.invictacostarica.com', '', $value);
        }
        $this->attributes['imagen'] = $value;
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function getImagenesExtraAttribute(): array
    {
        return $this->images->pluck('url')->values()->toArray();
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

    /**
     * Construye el título de visualización igual al que usa
     * resources/views/pages/product-detail.blade.php
     *
     * Formato: "Reloj Invicta {coleccion} para {genero} ({modelo}) - {size} mm"
     *  - Se omite la colección si está vacía o es "otros"
     *  - Se omite "para {genero}" si está vacío o es "unisex"
     */
    public static function buildDisplayTitle(?string $coleccion, ?string $genero, ?string $modelo, ?string $size, ?string $tipoMovimiento = null): string
    {
        $size = $size ? preg_replace('/\s*mm$/i', '', $size) : '';

        $title = 'Reloj Invicta ';

        if ($coleccion && strtolower($coleccion) !== 'otros') {
            $title .= $coleccion . ' ';
        }

        if ($genero && strtolower($genero) !== 'unisex') {
            $title .= 'para ' . $genero . ' ';
        }

        if ($tipoMovimiento && in_array(strtolower($tipoMovimiento), ['cuarzo', 'automatico', 'automático', 'automatic'], true)) {
            $title .= strtolower($tipoMovimiento) === 'cuarzo' ? 'cuarzo ' : 'automático ';
        }

        $title .= '(' . $modelo . ') - ' . $size . ' mm';

        return $title;
    }

    public function getDisplayTitleAttribute(): string
    {
        return static::buildDisplayTitle(
            $this->coleccion,
            $this->genero,
            $this->modelo,
            $this->size,
            $this->tipo_movimiento,
        );
    }

    /**
     * Aplica una busqueda de texto libre sobre los campos relevantes,
     * incluyendo el `title` (que ahora agrega coleccion/genero/tipo/modelo/size).
     *
     * Tokeniza el texto por espacios y aplica AND entre tokens; cada token
     * se busca via LIKE en OR sobre los campos (modelo, title, coleccion,
     * color, genero, brazalete, tipo_movimiento, descripcion).
     *
     * Normaliza sufijos "mm" (ej: "48mm" -> "48") para que coincidan con
     * el title que usa "48.0 mm".
     */
    public static function applyTextSearch($query, string $text): void
    {
        $rawTokens = preg_split('/\s+/', trim($text));
        if ($rawTokens === false) return;

        $tokens = [];
        foreach ($rawTokens as $tok) {
            $tok = trim($tok, ".,()[]{}");
            if ($tok === '') continue;
            // "48mm" -> "48", "40.0mm" -> "40.0"
            if (preg_match('/^(.+?)mm$/i', $tok, $m)) {
                $tok = $m[1];
            }
            if (mb_strlen($tok) >= 2) {
                $tokens[] = $tok;
            }
        }
        $tokens = array_values(array_unique($tokens));

        if (empty($tokens)) return;

        foreach ($tokens as $tok) {
            $query->where(function ($q) use ($tok) {
                $q->where('modelo', 'like', "%{$tok}%")
                  ->orWhere('title', 'like', "%{$tok}%")
                  ->orWhere('coleccion', 'like', "%{$tok}%")
                  ->orWhere('color', 'like', "%{$tok}%")
                  ->orWhere('genero', 'like', "%{$tok}%")
                  ->orWhere('brazalete', 'like', "%{$tok}%")
                  ->orWhere('tipo_movimiento', 'like', "%{$tok}%")
                  ->orWhere('descripcion', 'like', "%{$tok}%");
            });
        }
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

