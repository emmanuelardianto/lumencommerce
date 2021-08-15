<?php

namespace App\Http\Controllers;

use App\Models\ProductVariantRef;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;

class ProductVariantRefController extends Controller {
    public function list(Request $request) {
        try {
            $search = $request->get('search');
            $productsVariantRef = ProductVariantRef::orderBy('name');
            if(!is_null($search)) {
                $productsVariantRef = $productsVariantRef->where('name', 'like', '%'.$search.'%');
            }
            if($request->get('isPagination') === 'false' ) {
                $productsVariantRef = $productsVariantRef->get();
            } else {
                $perPage = $request->get('per_page');
                $productsVariantRef = $productsVariantRef->paginate($perPage);  
            } 
            return response()->json([
                "data" => $productsVariantRef,
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
            return response()->json([
                "data" => ProductVariantRef::where('id', $id)->first(),
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
                'name' => ['required', Rule::in(['size', 'color'])],
                'text' => 'required',
                'value' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $productVariantRef = new ProductVariantRef();
    
            $productVariantRef = $productVariantRef->fill([
                'name' => $request->get('name'),
                'text' => $request->get('text'),
                'value' => $request->get('value'),
            ]);
        
            $productVariantRef->save();
            return response()->json([
                "data" => $productVariantRef,
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
                'id' => 'exists:product_variant_refs,id',
                'name' => ['required', Rule::in(['size', 'color'])],
                'text' => 'required',
                'value' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $productVariantRef = ProductVariantRef::where('id', $request->get('id'))->first();
    
            $productVariantRef = $productVariantRef->fill([
                'name' => $request->get('name'),
                'text' => $request->get('text'),
                'value' => $request->get('value'),
            ]);
        
            $productVariantRef->save();
            return response()->json([
                "data" => $productVariantRef,
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