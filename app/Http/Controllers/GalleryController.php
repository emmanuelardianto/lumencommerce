<?php

namespace App\Http\Controllers;

use App\Models\ Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Support\Facades\Auth;

class  GalleryController extends Controller
{
    public function list(Request $request)
    {
        try {
            return response()->json([
                "data" =>  Gallery::whereIn('id', collect($request->get('id'))->toArray())->get(),
                "status" => 200,
                "success" => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 200,
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }
}
