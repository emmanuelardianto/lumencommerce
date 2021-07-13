<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
            $products = Product::orderBy('name');
            if(!is_null($search)) {
                $products = $products->where('name', 'like', '%'.$search.'%');
            }
            $products = $product->paginate(20);
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
        $product = Product::where('id', $id)->first();
        return response()->json($product);
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
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
            
            $product = new Product();
    
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.product.update', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.product')->with('success', 'Data deleted.');
    }
}
