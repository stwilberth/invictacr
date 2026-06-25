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
        return $this->precio_venta == 0;
    }
}
