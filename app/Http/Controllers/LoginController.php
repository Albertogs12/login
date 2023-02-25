<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        if ($request->has('name')) {
            $credentials = $request->validate([
                'name' => 'required',
                'password' => 'required',
            ]);
        } elseif ($request->has('email')) {
            $credentials = $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);
        }

        if (Auth::guard('api')->check()) {
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

    public function whoAmI(Request $request)
    {
        return response([
            'success' => true,
            'message' => 'User obtained successfully',
            'data' => Auth::guard('api')->user()
        ], 200);
    }

    public function tremendo(Request $request)
    {
        return response([
            'success' => true,
            'message' => 'Estas tremendo',
            'data' => null
        ], 200);
    }

    public function logout(Request $request)
    {
        Auth::guard('api')->user()->tokens()->delete();
        return response([
            'success' => true,
            'message' => 'Logged out successfully',
            'data' => null
        ], 200);
    }

    public function signUp(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users',
                'password' => 'required|string',
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $response = [
                'success' => true,
                'message' => 'User created successfully',
                'data' => User::findOrFail(DB::getPdo()->lastInsertId())
            ];
            return response()->json($response, 200);
        } catch (Throwable $e) {
            report($e);

            $response = [
                'success' => false,
                'message' => 'User has not been created',
                'data' => null
            ];
            return response()->json($response, 422);
        }
    }
}
