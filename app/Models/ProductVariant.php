<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type_name',
        'type_value',
        'qty',
        'status',
        'image_url',
        'price'
    ];
}
