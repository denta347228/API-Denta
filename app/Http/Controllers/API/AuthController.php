<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user(); // Mendapatkan data user yang terautentikasi

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token
            ]
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:5',
            'customer_address' => 'required|string|min:5'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'customer_address' => $request->customer_address
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ]
            ]
        ], 200);
    }

    public function me()
    {
        return response()->json(auth()->user(), 200);
    }

    public function logout()
    {
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if ($removeToken) {
            return response()->json(
                [

                    'succes' => true,
                    'message' => 'logout berhasil'

                ]
            );

        }
    }


}
