<?php

use Illuminate\Support\Facades\Route;

// Trang login form (hiển thị HTML form + JS gọi API)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Dashboard page (hiển thị view Blade, JS sẽ tự fetch user qua API)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Trang quản lý tài khoản
Route::get('/users', function () {
    return view('users');
})->name('users');

// Trang danh sách phòng học (chỉ trả về view, không ảnh hưởng API)
Route::get('/rooms', function () {
    return view('rooms');
})->name('rooms');

// Trang thiết bị (chỉ trả về view, không ảnh hưởng API)
Route::get('/devices', function () {
    return view('devices');
});
