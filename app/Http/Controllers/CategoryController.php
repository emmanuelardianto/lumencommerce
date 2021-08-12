<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class CategoryController extends Controller
{
    public function list(Request $request)
    {
        try {
            $search = $request->get('search');
            $withProduct = $request->get('with_product');
            $categories = Category::orderBy('name');
            if(!is_null($withProduct)) {
                $categories = $categories->with('products');
            }
            if(!is_null($search)) {
                $categories = $categories->where('name', 'like', '%'.$search.'%');
            }
            if($request->get('isPagination') === 'false' ) {
                $categories = $categories->get();
            } else {
                $perPage = $request->get('per_page');
                $categories = $categories->paginate($perPage);  
            } 
            return response()->json([
                "data" => $categories,
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
            $category = Category::where('id', $id)->first();
            return response()->json([
                "data" => $category,
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
            
            $category = new Category();
    
            $category = $category->fill([
                'name' => $request->get('name'),
                'gender' => $request->get('gender')
            ]);
        
            $category->save();
            return response()->json([
                "data" => $category,
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
            
            $category = Category::where('id', $request->get('id'))->first();
    
            $category = $category->fill([
                'name' => $request->get('name'),
                'gender' => $request->get('gender')
            ]);
        
            $category->save();
            return response()->json([
                "data" => $category,
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
            
            $category = Category::where('id', $request->get('id'))->first();
            if (is_null($category)) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => "Data not found."
                ]);
            }

        
            $category->delete();
            return response()->json([
                "data" => $category,
                "status" => 200,
                "success" => true,
                "message" => "Successfully deleted category."
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 200,
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function getCategoryWithProduct(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                ]);
            }
            
            $category = Category::where('id', $request->get('id'))->first();
            if (is_null($category)) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                ]);
            }
            $products = Product::with('product_variants')->where('category_id', $category->id)->paginate(20);
            $category['products'] = $products;
            return response()->json([
                "data" => $category,
                "status" => 200,
                "success" => true,
                "message" => "Successfully deleted category."
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
