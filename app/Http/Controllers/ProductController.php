<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class ProductController extends Controller
{
    public function list(Request $request)
    {
        try {
            $search = $request->get('search');
            $perPage = $request->get('per_page');
            $products = Product::with('category:id,name')->orderBy('name');
            if(!is_null($search)) {
                $products = $products->where('name', 'like', '%'.$search.'%');
            }
            $products = $products->paginate($perPage);
            return response()->json([
                "data" => $products,
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
        $product = Product::with('product_variants')->where('id', $id)->first();
        return response()->json($product);
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'exists:categories,id',
                'name' => 'required|max:250',
                'description' => 'max:1000',
                'gender' => 'required',
                'variant_type' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $product = new Product();
    
            $product = $product->fill([
                'category_id' => $request->get('category'),
                'name' => $request->get('name'),
                'slug' => Str::slug($request->get('name')),
                'description' => $request->get('description'),
                'gender' => $request->get('gender'),
                'variant_type' => $request->get('variant_type')
            ]);
        
            $product->save();
            $variants = collect($request->get('variants'))->map(function($item) use($product) {
                $item['product_id'] = $product->id;
                return $item;
            });

            ProductVariant::insert($variants->toArray());

            return response()->json([
                "data" => $product,
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
                'id' => 'required|exists:products,id',
                'category_id' => 'exists:categories,id',
                'name' => 'required|max:250',
                'price' => 'required|numeric',
                'status' => 'boolean',
                'description' => 'max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $product = Product::where('id', $request->get('id'))->first();
    
            $product = $product->fill([
                'category_id' => $request->get('category_id'),
                'name' => $request->get('name'),
                'slug' => Str::slug($request->get('name')),
                'description' => $request->get('description'),
                'status' => $request->get('status'),
                'price' => $request->get('price'),
            ]);
        
            $product->save();
            return response()->json([
                "data" => $product,
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
            
            $product = Product::where('id', $request->get('id'))->first();
            if (is_null($product)) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => "Data not found."
                ]);
            }

        
            $product->delete();
            return response()->json([
                "data" => $product,
                "status" => 200,
                "success" => true,
                "message" => "Successfully deleted product."
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 200,
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function productAssets() {
        try {
            $data['colors'] = config('product.colors');
            $data['sizes'] = config('product.sizes');
            $data['genders'] = config('product.genders');
            $data['categories'] = Category::orderBy('name')->get();
            return response()->json([
                "data" => $data,
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
