<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactionitem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'product_variant_id',
        'name',
        'price',
        'discount',
        'qty',
        'weight',
        'shipping_cost'
    ];
}
