<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;

// Trang đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('guest')->name('login');

// Xử lý đăng nhập
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

// Đăng xuất
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard (chỉ cho user đã đăng nhập)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// Các trang yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    // Trang quản lý tài khoản
    Route::get('/users', function () {
        return view('users');
    })->name('users');

    // Trang danh sách phòng học
    Route::get('/rooms', [RoomController::class, 'showRooms'])->name('rooms');

    // Trang thiết bị
    Route::get('/devices', function () {
        return view('devices');
    })->name('devices');

    // API lấy thông tin user
    Route::get('/api/user', [AuthController::class, 'profile']);
});
