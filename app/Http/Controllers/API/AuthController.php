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
            'email' => 'required | email',
            'password' => 'required | string|min:6',


        ]);

        $credentials = $request->only('email', 'password');
        if (!$token = auth()->attempt($credentials))
            return response()->json(['error' => 'Unauthorized'], 40);


        return response()->json([
            'acces_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60

        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'customer_address' => 'required | string|min:6'
        ]);

        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),

            ]
        );
        return response()->json(['message' => "Register Succesfully"], 200);

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
