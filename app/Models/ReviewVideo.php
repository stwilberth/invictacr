<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewVideo extends Model
{
    protected $fillable = [
        "stream_uid",
        "nombre",
        "activo",
        "orden",
    ];

    protected $casts = [
        "activo" => "boolean",
        "orden" => "integer",
    ];

    public function scopeActivos($query)
    {
        return $query->where("activo", true);
    }
}
