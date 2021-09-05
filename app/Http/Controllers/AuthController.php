<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    function login() {
        $credentials = request(['email', 'password']);
 
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
 
        return $this->respondWithToken($token);
    }
 
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'ログアウトしました。']);
    }
 
    public function me()
    {
        return response()->json(auth()->user());
    }
 
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth("api")->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'gender' => ['required', Rule::in(['male', 'female', 'other'])],
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
                'name' => 'member',
                'is_admin' => false,
                'status' => false,
                'password' => Hash::make($request->get('password')),
                'gender' => $request->get('gender')
            ]);

            $user->save();

            return response()->json([
                "message" => "Registered.",
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
    
    public function refresh() {
        return $this->createNewToken(auth('users')->refresh());
    }
 
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('users')->factory()->getTTL() * 60
        ]);
    }

    public function changePassword(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:users',
                'password' => 'required',
                'old_password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }

            $user = User::where('id', $request->get('id'))->first();

            if(!Hash::check($request->get('old_password'), $user->password)) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => 'User Id or Password doesn\'t match'
                ]);
            }
            $user->password = Hash::make($request->get('passworod'));
            $user->save();

            return response()->json([
                "message" => "Successfuly changed password.",
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

    public function forgotPassword(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:users',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => $validator->errors()->all()
                ]);
            }

            \DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => Str::random(40),
                'created_at' => Carbon::now()
            ]);

            //send email

            return response()->json([
                "message" => "Succesfully created token",
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
