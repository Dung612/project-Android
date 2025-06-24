<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// API login/register/logout dùng Sanctum
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/profile', [ProfileController::class, 'show']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::middleware('auth:sanctum')->get('/rooms/{room}/bookings', function ($roomId) {
    return \App\Models\Booking::where('room_id', $roomId)->orderByDesc('start_time')->get();
});

Route::middleware('auth:sanctum')->get('/room-types', function () {
    return \App\Models\RoomType::all();
});

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    $role = $request->query('role');
    $query = User::with(['roles']);
    if ($role === 'user') {
        $query->whereHas('roles', function($q) {
            $q->where('name', 'user');
        });
    } elseif ($role === 'staff') {
        $query->whereDoesntHave('roles', function($q) {
            $q->where('name', 'user');
        });
    }
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('full_name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ;
        });
    }
    $users = $query->paginate(10);
    return $users;
});

Route::middleware('auth:sanctum')->post('/users', function (Request $request) {
    $data = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'is_verified' => 'required|boolean',
    ]);
    $user = new User();
    $user->full_name = $data['full_name'];
    $user->email = $data['email'];
    $user->password = \Hash::make($data['password']);
    $user->is_verified = $data['is_verified'];
    $user->save();
    return response()->json([
        'id' => $user->id,
        'full_name' => $user->full_name,
        'email' => $user->email,
        'is_verified' => $user->is_verified,
        'roles' => $user->roles,
        'message' => 'Tạo tài khoản thành công!'
    ]);
});

Route::middleware('auth:sanctum')->put('/users/{user}', function (Request $request, User $user) {
    $data = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email',
        'password' => 'nullable|string|min:6',
        'is_verified' => 'required|boolean',
    ]);
    if (!empty($data['password'])) {
        $user->password = \Hash::make($data['password']);
    }
    $user->full_name = $data['full_name'];
    $user->email = $data['email'];
    $user->is_verified = $data['is_verified'];
    $user->save();
    return response()->json([
        'id' => $user->id,
        'full_name' => $user->full_name,
        'email' => $user->email,
        'is_verified' => $user->is_verified,
        'roles' => $user->roles,
        'message' => 'Cập nhật tài khoản thành công!'
    ]);
});

Route::middleware('auth:sanctum')->delete('/users/{user}', function (User $user) {
    $user->delete();
    return response()->json(['success' => true]);
});

Route::middleware('auth:sanctum')->get('/roles', function () {
    return Role::all();
});

Route::middleware('auth:sanctum')->get('/users/{user}/roles', function (User $user) {
    return $user->roles;
});

Route::middleware('auth:sanctum')->post('/users/{user}/roles', function (Request $request, User $user) {
    $roleIds = $request->input('role_ids', []);
    $user->roles()->sync($roleIds);
    return $user->roles;
});

Route::middleware('auth:sanctum')->delete('/users/{user}/roles/{role}', function (User $user, Role $role) {
    $user->roles()->detach($role->id);
    return response()->json(['success' => true]);
});

Route::middleware('auth:sanctum')->get('/users/{user}', function (User $user) {
    return [
        'id' => $user->id,
        'full_name' => $user->full_name,
        'email' => $user->email,
        'is_verified' => $user->is_verified,
        'roles' => $user->roles,
    ];
});

Route::middleware('auth:sanctum')->get('/classes', function () {
    return \App\Models\ClassModel::all();
});

Route::middleware('auth:sanctum')->get('/devices', [\App\Http\Controllers\DeviceController::class, 'index']);
Route::middleware('auth:sanctum')->post('/devices', [\App\Http\Controllers\DeviceController::class, 'store']);
Route::middleware('auth:sanctum')->put('/devices/{device}', [\App\Http\Controllers\DeviceController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/devices/{device}', [\App\Http\Controllers\DeviceController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/devices/{device}', [\App\Http\Controllers\DeviceController::class, 'show']);

Route::middleware('auth:sanctum')->get('/device-types', [\App\Http\Controllers\DeviceTypeController::class, 'index']);
Route::middleware('auth:sanctum')->post('/device-types', [\App\Http\Controllers\DeviceTypeController::class, 'store']);
Route::middleware('auth:sanctum')->put('/device-types/{device_type}', [\App\Http\Controllers\DeviceTypeController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/device-types/{device_type}', [\App\Http\Controllers\DeviceTypeController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/departments', function () {
    return \App\Models\Department::all();
});

// CRUD cho vai trò (Role)
Route::middleware('auth:sanctum')->post('/roles', function (Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:50|unique:roles,name',
        'description' => 'nullable|string',
    ]);
    $role = \App\Models\Role::create($data);
    return response()->json($role, 201);
});
Route::middleware('auth:sanctum')->put('/roles/{role}', function (Request $request, \App\Models\Role $role) {
    $data = $request->validate([
        'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
        'description' => 'nullable|string',
    ]);
    $role->update($data);
    return response()->json($role);
});
Route::middleware('auth:sanctum')->delete('/roles/{role}', function (\App\Models\Role $role) {
    if (in_array($role->name, ['admin', 'manager'])) {
        return response()->json(['message' => 'Không được xóa vai trò admin hoặc manager!'], 403);
    }
    $role->delete();
    return response()->json(['success' => true]);
});

// Cập nhật thông tin cá nhân cho user đang đăng nhập
Route::middleware('auth:sanctum')->put('/user', function (Request $request) {
    $user = $request->user();
    $data = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email',
        'password' => 'nullable|string|min:6',
    ]);
    if (!empty($data['password'])) {
        $user->password = \Hash::make($data['password']);
    }
    $user->full_name = $data['full_name'];
    $user->email = $data['email'];
    $user->save();
    return response()->json([
        'id' => $user->id,
        'full_name' => $user->full_name,
        'email' => $user->email,
        'roles' => $user->roles,
        'message' => 'Cập nhật thông tin thành công!'
    ]);
}); 