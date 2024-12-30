<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $input = $request->validated();

        $credentials = [
            'email' => $input['email'],
            'password' => $input['password'],
        ];

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    public function validateToken(): JsonResponse
    {
        try {
            // Tenta autenticar com o token JWT
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(['valid' => true, 'user' => $user]);
        } catch (\Exception $e) {
            // Retorna que o token não é válido em caso de erro
            return response()->json(['valid' => false], 401);
        }
    }
}
