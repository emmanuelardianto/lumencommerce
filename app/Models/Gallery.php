<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'tags',
    ];

    protected $casts = ['tags' => 'json'];

    public function getPathAttribute($value) {
        return url($value);
    }

    public static function SaveUpload($file, $path, $name = null) {
        $file_name = $file->getClientOriginalName();
        $generated_new_name = $name.'-'.time() . '.' . $file->getClientOriginalExtension();
        $file->move($path, $generated_new_name);

        $gallery = new Gallery();
        $gallery->fill([
            'path' => $path.'/'.$generated_new_name
        ]);

        $gallery->save();

        return $gallery->id;
    }
}
