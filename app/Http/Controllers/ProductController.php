<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $products = Product::orderBy('name');
        if(!is_null($search)) {
            $products = $products->where('name', 'like', '%'.$search.'%');
        }
        $products = $products->paginate(20);
        return response()->json($products);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.product.update', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image_url = '';
        if(is_null($product)) {
            $product = new Product();
        } else {
            $image_url = $product->getRawOriginal('image_url');
        }
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_url = \Str::slug($request->get('name')).time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $image_url);
        }

        $product = $product->fill([
            'category_id' => $request->get('category_id'),
            'name' => $request->get('name'),
            'slug' => \Str::slug($request->get('name')),
            'description' => $request->get('description'),
            'status' => $request->get('status'),
            'price' => $request->get('price'),
            'image_url' => $image_url
        ]);
    

        $product->save();
        return redirect()->route('admin.product.edit', compact('product'))->with('success', 'Data saved.');
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
