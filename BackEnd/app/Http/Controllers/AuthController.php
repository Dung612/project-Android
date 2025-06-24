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
use Laravel\Sanctum\HasApiTokens;

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

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            
            $user = User::where('email', $credentials['email'])->first();
            
            if (!$user) {
                Log::info('Đăng nhập thất bại: email không tồn tại', ['email' => $credentials['email']]);
                return back()
                    ->withErrors(['email' => 'Email không tồn tại trong hệ thống'])
                    ->withInput($request->except('password'));
            }

            if (!Hash::check($credentials['password'], $user->password)) {
                Log::info('Đăng nhập thất bại: mật khẩu không đúng', ['email' => $credentials['email']]);
                return back()
                    ->withErrors(['password' => 'Mật khẩu không chính xác'])
                    ->withInput($request->except('password'));
            }

            if (!$user->is_verified) {
                Log::info('Đăng nhập thất bại: tài khoản chưa được xác thực', ['email' => $credentials['email']]);
                return back()
                    ->withErrors(['email' => 'Tài khoản chưa được xác thực'])
                    ->withInput($request->except('password'));
            }

            Auth::login($user);
            $request->session()->regenerate();
            
            Log::info('Đăng nhập thành công', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            Log::error('Lỗi đăng nhập', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withErrors(['email' => 'Có lỗi xảy ra, vui lòng thử lại sau'])
                ->withInput($request->except('password'));
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            Log::info('Đăng xuất thành công');
            return redirect('/login');
        } catch (\Exception $e) {
            Log::error('Lỗi đăng xuất', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['message' => 'Có lỗi xảy ra khi đăng xuất']);
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                Log::warning('Không tìm thấy thông tin người dùng');
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }

            Log::info('Lấy thông tin profile thành công', ['user_id' => $user->id]);
            return response()->json([
                'user' => $user->load('roles')
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi lấy thông tin profile', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau'
            ], 500);
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
}
