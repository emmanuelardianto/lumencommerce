<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'product_variant_id',
        'title',
        'nick_name',
        'description',
        'comfort_rating',
        'rating',
        'gender',
        'size',
        'height_range',
        'weight_range',
        'shoe_size',
        'age_range',
        'status',
    ];
}
