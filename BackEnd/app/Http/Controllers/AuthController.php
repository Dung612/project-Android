<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_verified' => false
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng ký thành công',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        // Kiểm tra thông tin đăng nhập
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !\Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Thông tin đăng nhập không chính xác'
            ], 401);
        }

        // Kiểm tra tài khoản đã xác thực chưa
        // Chỉ kiểm tra xác thực trong môi trường production
        if (app()->environment('production') && !$user->is_verified) {
            return response()->json([
                'message' => 'Tài khoản chưa được xác thực',
                'email' => $user->email
            ], 403);
        }

        // Xóa token cũ nếu có
        $user->tokens()->delete();
        
        // Tạo token mới
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Đăng xuất thành công'
        ]);
    }
} 


