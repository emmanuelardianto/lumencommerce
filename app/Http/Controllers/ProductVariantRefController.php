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
            $productsVairantRef = ProductVariantRef::orderBy('name');
            if(!is_null($search)) {
                $productsVairantRef = $productsVairantRef->where('name', 'like', '%'.$search.'%');
            }
            return response()->json([
                "data" => $productsVairantRef->get(),
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
                "data" => ProductVariantRef::find($id)->get(),
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