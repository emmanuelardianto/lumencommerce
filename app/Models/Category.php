<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function getRouteKeyName() {
        return 'slug';
    }

    public function getProductsAttribute() {
        return \App\Models\Product::where('category_id', $this->id)->where('status', 1)->paginate(24);
    }
}
