<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class WishlistController extends Controller
{
    public function list(Request $request)
    {
        try {
            $wishlists = Wishlist::with(['product', 'product_variant'])->orderBy('created_at')->where('user_id', $request->get('user_id'));
            if($request->get('product_id')) {
                $wishlists = $wishlists->where('product_id', $request->get('product_id'));
            }
            return response()->json([
                "data" => $wishlists->get(),
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

    public function toggle(Request $request)
    {
        try {
            $wishlists = Wishlist::where('user_id', $request->get('user_id'))
                                    ->where('product_id', $request->get('product_id'))
                                    ->where('product_variant_id', $request->get('product_variant_id'));

            if(count($wishlists->get()) > 0){
                $wishlists->delete();
                return response()->json([
                    "data" => $wishlists,
                    "status" => 200,
                    "success" => true,
                    "message" => "Successfully deleted wishlist."
                ]);
            }
            
            $wishlist = new Wishlist();
    
            $wishlist = $wishlist->fill([
                'user_id' => $request->get('user_id'),
                'product_id' => $request->get('product_id'),
                'product_variant_id' => Str::slug($request->get('product_variant_id')),
            ]);
        
            $wishlist->save();
            return response()->json([
                "data" => $wishlist,
                "status" => 200,
                "success" => true,
                "message" => "Added to wishlist."
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
