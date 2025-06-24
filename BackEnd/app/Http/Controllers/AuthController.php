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
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'full_name'   => $request->full_name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'is_verified' => true, // ✅ Tạm TRUE để test dễ (bạn chỉnh lại sau)
        ]);

        return response()->json([
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'is_verified' => $user->is_verified,
            'message' => 'Đăng ký thành công. Vui lòng đăng nhập để tiếp tục.'
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        Log::info('Đăng nhập thử với:', $credentials);

        // ✅ Dùng Auth::attempt để check session-based credential
        if (!Auth::attempt($credentials)) {
            Log::warning('Đăng nhập thất bại với:', $credentials);
            return response()->json([
                'message' => 'Thông tin đăng nhập không chính xác'
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ✅ Kiểm tra đã xác thực chưa
        if (!$user->is_verified) {
            $user->tokens()->delete();
            Auth::logout();
            return response()->json([
                'message' => 'Tài khoản chưa được xác thực',
                'email' => $user->email
            ], 403);
        }

        // ✅ Xóa token cũ & tạo mới
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user->load('roles')),
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

    public function showLoginForm()
    {
        return view('auth.login');
    }
}
