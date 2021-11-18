<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function list(Request $request)
    {
        try {
            return response()->json([
                "data" => Transaction::where('user_id', $request->get('user_id'))->get(),
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
            $transaction = Transaction::where('id', $id)->first();
            return response()->json([
                "data" => $transaction,
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
                'prefecture' => 'required',
                'city' => 'required',
                'transaction1' => 'required',
                'phone' => 'required',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $transaction = new Transaction();
    
            $transaction = $transaction->fill([
                'user_id' => $request->get('user_id'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'first_name_kana' => $request->get('first_name_kana'),
                'last_name_kana' => $request->get('last_name_kana'),
                'zip_code' => $request->get('zip_code'),
                'prefecture' => $request->get('prefecture'),
                'city' => $request->get('city'),
                'transaction1' => $request->get('transaction1'),
                'transaction2' => $request->get('transaction2'),
                'phone' => $request->get('phone'),
                'mobile_phone' => $request->get('mobile_phone'),
            ]);
        
            $transaction->save();
            return response()->json([
                "data" => $transaction,
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
                'prefecture' => 'required',
                'city' => 'required',
                'transaction1' => 'required',
                'phone' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $transaction = Transaction::where('id', $request->get('id'))->first();
    
            $transaction = $transaction->fill([
                'user_id' => $request->get('user_id'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'first_name_kana' => $request->get('first_name_kana'),
                'last_name_kana' => $request->get('last_name_kana'),
                'zip_code' => $request->get('zip_code'),
                'prefecture' => $request->get('prefecture'),
                'city' => $request->get('city'),
                'transaction1' => $request->get('transaction1'),
                'transaction2' => $request->get('transaction2'),
                'phone' => $request->get('phone'),
                'mobile_phone' => $request->get('mobile_phone'),
            ]);
        
            $transaction->save();
            return response()->json([
                "data" => $transaction,
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
            
            $transaction = Transaction::where('id', $request->get('id'))->first();
            if (is_null($transaction)) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => "Data not found."
                ]);
            }

            $transaction->delete();
            return response()->json([
                "data" => $transaction,
                "status" => 200,
                "success" => true,
                "message" => "Successfully deleted transaction."
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 200,
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function setDefault(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            $transaction = Transaction::where('id', $request->get('id'))->first();
            if (is_null($transaction)) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => "Data not found."
                ]);
            }

            Transaction::where('user_id', $request->get('user_id'))->update(['default' => false]);
            $transaction->default = true;
            $transaction->save();
            return response()->json([
                "data" => $transaction,
                "status" => 200,
                "success" => true,
                "message" => "Successfully set transaction as default."
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
