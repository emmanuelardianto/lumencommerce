<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'product_variant_id'
    ];

    public function product() {
        return $this->belongsTo(\App\Models\Product::class);
    }

    public function product_variant() {
        return $this->belongsTo(\App\Models\ProductVariant::class);
    }
}
