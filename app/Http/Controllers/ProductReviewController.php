<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class ProductReviewController extends Controller
{
    public function listByUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }

            $product_reviews = ProductReview::where('user_id', $request->get('user_id'))->get();

            return response()->json([
                "data" => $product_reviews,
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

    public function listByProduct(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }

            $product_reviews = ProductReview::where('product_id', $request->get('product_id'))->get();

            return response()->json([
                "data" => $product_reviews,
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

    public function getById($id) {
        try {
            $product_review = ProductReview::where('id', $id)->first();
            return response()->json([
                "data" => $product_review,
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

    public function getBySlug($slug) {
        try {
            $product_review = ProductReview::where('slug', $slug)->first();
            return response()->json([
                "data" => $product_review,
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

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'product_id' => 'required|exists:products,id',
                'product_variant_id' => 'required|exists:product_variants,id',
                'nick_name' => 'required',
                'title' => 'required',
                'description' => 'required',
                'comfort_rating' => 'required|numeric|min:1|max:5',
                'rating' => 'required|numeric|min:1|max:5',
                'gender' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $product_review = new ProductReview();
    
            $product_review = $product_review->fill([
                'user_id' => $request->get('user_id'),
                'product_id' => $request->get('product_id'),
                'product_variant_id' => $request->get('product_variant_id'),
                'nick_name' => $request->get('nick_name'),
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'comfort_rating' => $request->get('comfort_rating'),
                'rating' => $request->get('rating'),
                'gender' => $request->get('gender'),
                'size' => $request->get('size'),
                'height_range' => $request->get('height_range'),
                'weight_range' => $request->get('weight_range'),
                'shoe_size' => $request->get('shoe_size'),
                'age_range' => $request->get('age_range'),
            ]);
        
            $product_review->save();
            return response()->json([
                "data" => $product_review,
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

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required|max:250',
                'gender' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $product_review = ProductReview::where('id', $request->get('id'))->first();
    
            $product_review = $product_review->fill([
                'name' => $request->get('name'),
                'gender' => $request->get('gender'),
                'slug' => Str::slug($request->get('name')),
            ]);
        
            $product_review->save();
            return response()->json([
                "data" => $product_review,
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

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $product_review = ProductReview::where('id', $request->get('id'))->first();
            if (is_null($product_review)) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => "Data not found."
                ]);
            }

        
            $product_review->delete();
            return response()->json([
                "data" => $product_review,
                "status" => 200,
                "success" => true,
                "message" => "Successfully deleted product_review."
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 200,
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function getProductReviewWithProduct(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'slug' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                ]);
            }
            
            $product_review = ProductReview::where('slug', $request->get('slug'))->first();
            if (is_null($product_review)) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                ]);
            }
            $products = Product::with('product_variants')->where('product_review_id', $product_review->id)->paginate(20);
            $product_review['products'] = $products;
            return response()->json([
                "data" => $product_review,
                "status" => 200,
                "success" => true,
                "message" => "Successfully deleted product_review."
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
