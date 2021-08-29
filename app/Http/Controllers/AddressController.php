<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function list(Request $request)
    {
        try {
            return response()->json([
                "data" => Address::where('user_id', $request->get('user_id'))->get(),
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
            $address = Address::where('id', $id)->first();
            return response()->json([
                "data" => $address,
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
                'first_name' => 'required',
                'last_name' => 'required',
                'first_name_kana' => 'required',
                'last_name_kana' => 'required',
                'zip_code' => 'required',
                'perfecture' => 'required',
                'city' => 'required',
                'address1' => 'required',
                'phone' => 'required',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $address = new Address();
    
            $address = $address->fill([
                'user_id' => $request->get('user_id'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'first_name_kana' => $request->get('first_name_kana'),
                'last_name_kana' => $request->get('last_name_kana'),
                'zip_code' => $request->get('zip_code'),
                'perfecture' => $request->get('perfecture'),
                'city' => $request->get('city'),
                'address1' => $request->get('address1'),
                'address2' => $request->get('address2'),
                'phone' => $request->get('phone'),
                'mobile_phone' => $request->get('mobile_phone'),
            ]);
        
            $address->save();
            return response()->json([
                "data" => $address,
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
                'user_id' => 'required|exists:users,id',
                'first_name' => 'required',
                'last_name' => 'required',
                'first_name_kana' => 'required',
                'last_name_kana' => 'required',
                'zip_code' => 'required',
                'perfecture' => 'required',
                'city' => 'required',
                'address1' => 'required',
                'phone' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $address = Address::where('id', $request->get('id'))->first();
    
            $address = $address->fill([
                'user_id' => $request->get('user_id'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'first_name_kana' => $request->get('first_name_kana'),
                'last_name_kana' => $request->get('last_name_kana'),
                'zip_code' => $request->get('zip_code'),
                'perfecture' => $request->get('perfecture'),
                'city' => $request->get('city'),
                'address1' => $request->get('address1'),
                'address2' => $request->get('address2'),
                'phone' => $request->get('phone'),
                'mobile_phone' => $request->get('mobile_phone'),
            ]);
        
            $address->save();
            return response()->json([
                "data" => $address,
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
            
            $address = Address::where('id', $request->get('id'))->first();
            if (is_null($address)) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => "Data not found."
                ]);
            }

            $address->delete();
            return response()->json([
                "data" => $address,
                "status" => 200,
                "success" => true,
                "message" => "Successfully deleted address."
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
