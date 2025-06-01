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
use Illuminate\Validation\ValidationException;

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
        // Có thể bạn muốn gửi email xác thực ở đây nếu is_verified là false
        // if (!$user->is_verified) {
        //     $user->sendEmailVerificationNotification(); // Laravel có sẵn nếu bạn đã thiết lập
        // }


        return response()->json([
            'message' => 'Đăng ký thành công. Vui lòng đăng nhập để tiếp tục.'
            // Hoặc nếu có bước xác thực email:
            // 'message' => 'Đăng ký thành công. Vui lòng kiểm tra email để xác thực tài khoản trước khi đăng nhập.'
        ], 201);
    }

public function login(LoginRequest $request): JsonResponse
{
    $credentials = $request->validated();

    // 1. Sử dụng Auth::attempt để kiểm tra và đăng nhập
    //    Nó sẽ tự tìm user và kiểm tra password.
    if (!Auth::attempt($credentials)) {
        return response()->json([
            'message' => 'Thông tin đăng nhập không chính xác'
        ], 401);
    }

    // 2. Nếu Auth::attempt thành công, user đã được đăng nhập.
    //    Bây giờ ta có thể lấy user bằng Auth::user().
    $user = Auth::user();

    // 3. Kiểm tra tài khoản đã xác thực chưa
    if (app()->environment('production') && !$user->is_verified) {
        // Nên xóa token/logout trước khi trả lỗi
        $user->tokens()->delete();
        Auth::logout(); // Nếu bạn có dùng session

        return response()->json([
            'message' => 'Tài khoản chưa được xác thực',
            'email' => $user->email
        ], 403);
    }

    // 4. Xóa token cũ và tạo token mới
    $user->tokens()->delete();
    $token = $user->createToken('auth_token')->plainTextToken;

    // 5. Trả về response, nhớ load('roles') để lồng dữ liệu
    return response()->json([
        'user' => new UserResource($user->load('roles')),
        'access_token' => $token,
        'token_type' => 'Bearer'
    ]);
}

    /**
     * Logout user (Revoke the token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công'
        ]);
    }
} 


