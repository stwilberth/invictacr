<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'url', 'order', 'type'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute($value)
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

    public function setUrlAttribute($value)
    {
        if ($value && str_starts_with($value, 'https://cdn.invictacostarica.com')) {
            $value = str_replace('https://cdn.invictacostarica.com', '', $value);
        }
        $this->attributes['url'] = $value;
    }
}
