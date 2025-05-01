<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Validators\AuthValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = AuthValidator::registerRules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "message" => "error validating data",
                "errors" => $validator->errors()
            ], 400);
        }

        $user = User::create([
            "firstname" => $request->firstname,
            "lastname" => $request->lastname,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        $user->save();

        return response()->json([
            "message" => "user created"
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->only('email', 'password');

        $token = JWTAuth::attempt($data);

        if (!$token) {
            return response()->json([
                "message" => "invalid credentials"
            ]);
        }

        return response()->json(compact('token'));
    }
}
