<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request) {

        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if(Auth::guard('sanctum')->check()){
            return response([
                'success' => false,
                'message' => 'User already logged in',
                'data' => null,
            ], 200);
        }

        if (Auth::attempt($credentials)) {
            
            $user = Auth::user();
            $token = $user->createToken('auth-token');

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'User or password does not exist',
            'data' => null
        ], 401);
    }

    public function whoAmI(Request $request) {
        return response([
            'success' => true,
            'message' => 'User obtained successfully',
            'data' => Auth::guard('sanctum')->user()
        ], 200);
    }

    public function tremendo(Request $request) {
        return response([
            'success' => true,
            'message' => 'Estas tremendo',
            'data' => null
        ], 200);
    }

    public function logout(Request $request) {
        Auth::guard('sanctum')->user()->tokens()->delete();
        return response([
            'success' => true,
            'message' => 'Logged out successfully',
            'data' => null
        ], 200);
    }
}

