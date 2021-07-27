<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class UserController extends Controller
{
    public function list(Request $request)
    {
        try {
            $search = $request->get('search');
            $perPage = $request->get('per_page');
            $users = User::orderBy('name');
            if(!is_null($search)) {
                $users = $users->where('name', 'like', '%'.$search.'%');
            }
            $users = $users->paginate($perPage);
            return response()->json([
                "data" => $users,
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
            $user = User::where('id', $id)->first();
            return response()->json([
                "data" => $user,
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
                'email' => 'required|email',
                'name' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $user = new User();
    
            $user = $user->fill([
                'email' => $request->get('email'),
                'name' => $request->get('name'),
                'is_admin' => $request->has('is_admin'),
                'status' => $request->has('status'),
                'password' => $request->has('password'),
                'gender' => $request->get('gender')
            ]);
        
            $user->save();
            return response()->json([
                "data" => $user,
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
                'email' => 'required|email|unique:users,email,'.$request->get('id'),
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }
            
            $user = User::where('id', $request->get('id'))->first();
    
            $user = $user->fill([
                'email' => $request->get('email'),
                'name' => $request->get('name'),
                'is_admin' => $request->get('is_admin'),
                'status' => $request->get('status'),
                'gender' => $request->get('gender')
            ]);
        
            $user->save();
            return response()->json([
                "data" => $user,
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
            
            $user = User::where('id', $request->get('id'))->first();
            if (is_null($user)) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => "Data not found."
                ]);
            }

        
            $user->delete();
            return response()->json([
                "data" => $user,
                "status" => 200,
                "success" => true,
                "message" => "Successfully deleted user."
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
