<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'banner',
        'slug',
        'status'
    ];

    public function items() {
        return $this->hasMany(\App\Models\CollectionItem::class);
    }

}
