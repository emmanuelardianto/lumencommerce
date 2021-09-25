<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class CollectionController extends Controller
{
    public function list(Request $request)
    {
        try {
            $search = $request->get('search');
            $perPage = 20;
            $collections = Collection::orderBy('name');
            if(!is_null($search)) {
                $collections = $collections->where('name', 'like', '%'.$search.'%');
            }
            if(!is_null($request->get('per_page'))) {
                $perPage = $request->get('per_page');
            }
            $collections = $collections->paginate($perPage);
            return response()->json([
                "data" => $collections,
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

    public function getById($id)
    {
        try {
            return response()->json([
                "data" => Collection::where('id', $id)->first(),
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
                'title' => 'required|max:250'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $collection = new Collection();
    
            $collection = $collection->fill([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'slug' => Str::slug($request->get('title')),
                'status' => $request->get('status')
            ]);
        
            $collection->save();
            if(!is_null($request->get('items'))) {
                $items = collect($request->get('items'))->map(function($item) use ($collection) {
                    return [
                        'collection_id' => $collection->id,
                        'product_id' => $item
                    ];
                });
                CollectionItem::insert($items->toArray());
            }
            return response()->json([
                "data" => $collection,
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
            
            $collection = Category::where('id', $request->get('id'))->first();
    
            $collection = $collection->fill([
                'name' => $request->get('name'),
                'gender' => $request->get('gender'),
                'slug' => Str::slug($request->get('name')),
            ]);
        
            $collection->save();
            return response()->json([
                "data" => $collection,
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
