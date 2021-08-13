<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_type1',
        'variant_value1',
        'variant_type2',
        'variant_value2',
        'qty',
        'status',
        'image_url',
        'price'
    ];
}
