<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\ProductVariant;
use App\Models\ProductVariantRef;
use App\Models\Gallery;
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
            $category = $request->get('category_id');
            $products = Product::with('category:id,name')->orderBy('name');
            if(!is_null($category))
                $products = $products->where('category_id', $category);
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
        try {
            $product = Product::with('product_variants')->where('id', $id)->first();
            $variantValue1ds = (collect($product->product_variants)->pluck('variant_value1'))->concat(collect($product->product_variants)->pluck('variant_value2'))->unique();
            $productVariantRefs = ProductVariantRef::whereIn('id', $variantValue1ds->toArray())->get();
            return response()->json([
                "data" => [
                    "product" => $product,
                    "assets" => $productVariantRefs
                ],
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
            $product = Product::with('product_variants')->where('slug', $slug)->first();
            $variantValue1ds = (collect($product->product_variants)->pluck('variant_value1'))->concat(collect($product->product_variants)->pluck('variant_value2'))->unique();
            $productVariantRefs = ProductVariantRef::whereIn('id', $variantValue1ds->toArray())->get();
            return response()->json([
                "data" => [
                    "product" => $product,
                    "assets" => $productVariantRefs
                ],
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

            if(!is_null($request->get('collections'))) {
                $items = collect($request->get('collections'))->map(function($item) use ($product) {
                    return [
                        'product_id' => $product->id,
                        'collection_id' => $item
                    ];
                });
                CollectionItem::insert($items->toArray());  
            }

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
                'category' => 'exists:categories,id',
                'name' => 'required|max:250',
                'description' => 'max:1000',
                'gender' => 'required',
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
                'category_id' => $request->get('category'),
                'name' => $request->get('name'),
                'slug' => Str::slug($request->get('name')),
                'description' => $request->get('description'),
                'gender' => $request->get('gender'),
            ]);
        
            $product->save();
            $variants = collect($request->get('variants'))->where('id', null)->map(function($item) use($product) {
                $item['product_id'] = $product->id;
                return $item;
            });

            ProductVariant::insert($variants->toArray());
            foreach(collect($request->get('variants')) as $exist) {
                ProductVariant::where('id', $exist['id'])->update($exist);
            }
            
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
            $data['colors'] = ProductVariantRef::where('name', 'color')->get();
            $data['sizes'] = ProductVariantRef::where('name', 'size')->get();
            $data['genders'] = config('product.genders');
            $data['categories'] = Category::orderBy('name')->get();
            $data['collections'] = Collection::orderBy('title')->get();
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

    public function galleryUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'img' => 'required'
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

            $file = $request->file('img');
            $upload_path = 'images/product';
            $file_name = $file->getClientOriginalName();
            $generated_new_name = $product->slug.'-'.time() . '.' . $file->getClientOriginalExtension();
            $file->move($upload_path, $generated_new_name);

            $gallery = new Gallery();
            $gallery->fill([
                'path' => $upload_path.'/'.$generated_new_name
            ]);

            $gallery->save();

            return response()->json([
                "data" => $gallery,
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
}
