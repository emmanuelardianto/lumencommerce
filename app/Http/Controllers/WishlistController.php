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
            $wishlists = Wishlist::with(['product', 'product_variant'])->orderBy('created_at')->where('user_id', $request->get('user_id'))->get();
            return response()->json([
                "data" => $wishlists,
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
            if(!is_null($request->get('id'))) {
                $wishlist = Wishlist::where('id', $request->get('id'))->first();
                if (is_null($wishlist)) {
                    return response()->json([
                        "status" => 401,
                        "success" => false,
                        "message" => "Data not found."
                    ]);
                }
            
                $wishlist->delete();
                return response()->json([
                    "data" => $wishlist,
                    "status" => 200,
                    "success" => true,
                    "message" => "Successfully deleted wishlist."
                ]);
            }
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'product_id' => 'required|exists:products,id',
                'product_variant_id' => 'required|exists:product_variants,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
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
