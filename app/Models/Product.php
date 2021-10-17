<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'gender',
        'variant_type',
        'images'
    ];

    public function getRouteKeyName() {
        return 'slug';
    }

    protected $casts = ['images' => 'json'];

    public function getImageUrlAttribute($value) {
        if(empty($value))
            return 'http://placehold.jp/150x150.png';

        return '/images/'.$value;
    }

    public function getPriceWithCurrencyAttribute() {
        return '$ '.$this->price;
    }

    public function getRelatedAttribute() {
        return self::where('category_id', $this->category_id)->where('status', 1)->take(6)->get();
    }

    public function getCategoryAttribute() {
        return \App\Models\Category::where('id', $this->category_id)->first();
    }

    public function category() {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function product_variants() {
        return $this->hasMany(\App\Models\ProductVariant::class);
    }
}
