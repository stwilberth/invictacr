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
        if (is_null($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);
        $accepted = config('brazaletes', []);

        foreach ($accepted as $valid) {
            if (strcasecmp($value, $valid) === 0) {
                return $valid;
            }
        }

        $lower = mb_strtolower($value);
        $map = [
            'acero inoxidable' => 'Acero Inoxidable',
            'stainless steel' => 'Acero Inoxidable',
            'cuero' => 'Cuero',
            'leather' => 'Cuero',
            'silicona' => 'Silicona',
            'silicone' => 'Silicona',
            'rubber' => 'Silicona',
            'goma' => 'Silicona',
            'plastico' => 'Plastico',
            'plastic' => 'Plastico',
            'titanio' => 'Titanio',
            'titanium' => 'Titanio',
        ];

        foreach ($map as $search => $replacement) {
            if (str_contains($lower, $search)) {
                return $replacement;
            }
        }

        return 'Otros';
    }
}
