<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'first_name_kana',
        'last_name_kana',
        'zip_code',
        'perfecture',
        'city',
        'address1',
        'address2',
        'phone',
        'mobile_phone',
        'default'
    ];
}
