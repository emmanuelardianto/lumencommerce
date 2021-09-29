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
            $collections = Collection::orderBy('title');
            if(!is_null($search)) {
                $collections = $collections->where('title', 'like', '%'.$search.'%');
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

    public function update(Request $request)
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
            if(!is_null($request->get('id'))) {
                $collection = Collection::where('id', $request->get('id'))->first();
                if(is_null($collection)) {
                    $collection = new Collection();    
                }
            } 
    
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
                "success" => true,
                "message" => "Data saved."
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
                'id' => 'required|exists:collections,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            Collection::where('id', $request->get('id'))->delete();
            CollectionItem::where('collection_id', $request->get('id'))->delete();

            return response()->json([
                "status" => 200,
                "success" => true,
                "message" => "Data has been deleted."
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
