<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\RoomController;

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/profile', [AuthController::class, 'profile']);
    
    Route::get('/dashboard-stats', [\App\Http\Controllers\RoomController::class, 'dashboardStats']);
    Route::get('/rooms', [\App\Http\Controllers\RoomController::class, 'index']);
    Route::post('/rooms', [\App\Http\Controllers\RoomController::class, 'store']);
    Route::get('/rooms/{room}', [\App\Http\Controllers\RoomController::class, 'show']);
    Route::put('/rooms/{room}', [\App\Http\Controllers\RoomController::class, 'update']);
    Route::delete('/rooms/{room}', [\App\Http\Controllers\RoomController::class, 'destroy']);

    Route::get('/rooms/{room}/devices', function ($roomId) {
        $items = DB::table('resource_items')
            ->where('room_id', $roomId)
            ->join('devices', 'resource_items.device_id', '=', 'devices.id')
            ->join('device_types', 'devices.device_type_id', '=', 'device_types.id')
            ->select(
                'devices.id as device_id',
                'devices.name as device_name',
                'device_types.name as device_type',
                'resource_items.quantity',
                'resource_items.note',
                'resource_items.status'
            )
            ->get();
        return $items;
    });

    Route::get('/rooms/{room}/bookings', function ($roomId) {
        return \App\Models\Booking::where('room_id', $roomId)->orderByDesc('start_time')->get();
    });

    Route::get('/room-types', function () {
        return \App\Models\RoomType::all();
    });

    Route::get('/users', function (Request $request) {
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

    Route::post('/users', function (Request $request) {
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

    Route::put('/users/{user}', function (Request $request, User $user) {
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

    Route::delete('/users/{user}', function (User $user) {
        $user->delete();
        return response()->json(['success' => true]);
    });

    Route::get('/roles', function () {
        return Role::all();
    });

    Route::get('/users/{user}/roles', function (User $user) {
        return $user->roles;
    });

    Route::post('/users/{user}/roles', function (Request $request, User $user) {
        $roleIds = $request->input('role_ids', []);
        $user->roles()->sync($roleIds);
        return $user->roles;
    });

    Route::delete('/users/{user}/roles/{role}', function (User $user, Role $role) {
        $user->roles()->detach($role->id);
        return response()->json(['success' => true]);
    });

    Route::get('/users/{user}', function (User $user) {
        return [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'is_verified' => $user->is_verified,
            'roles' => $user->roles,
        ];
    });

    Route::get('/classes', function () {
        return \App\Models\ClassModel::all();
    });

    Route::get('/devices', [\App\Http\Controllers\DeviceController::class, 'index']);
    Route::post('/devices', [\App\Http\Controllers\DeviceController::class, 'store']);
    Route::put('/devices/{device}', [\App\Http\Controllers\DeviceController::class, 'update']);
    Route::delete('/devices/{device}', [\App\Http\Controllers\DeviceController::class, 'destroy']);
    Route::get('/devices/{device}', [\App\Http\Controllers\DeviceController::class, 'show']);

    Route::get('/device-types', [\App\Http\Controllers\DeviceTypeController::class, 'index']);
    Route::post('/device-types', [\App\Http\Controllers\DeviceTypeController::class, 'store']);
    Route::put('/device-types/{device_type}', [\App\Http\Controllers\DeviceTypeController::class, 'update']);
    Route::delete('/device-types/{device_type}', [\App\Http\Controllers\DeviceTypeController::class, 'destroy']);

    Route::get('/departments', function () {
        return \App\Models\Department::all();
    });

    // CRUD cho vai trò (Role)
    Route::post('/roles', function (Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'description' => 'nullable|string',
        ]);
        $role = \App\Models\Role::create($data);
        return response()->json($role, 201);
    });
    Route::put('/roles/{role}', function (Request $request, \App\Models\Role $role) {
        $data = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
        ]);
        $role->update($data);
        return response()->json($role);
    });
    Route::delete('/roles/{role}', function (\App\Models\Role $role) {
        if (in_array($role->name, ['admin', 'manager'])) {
            return response()->json(['message' => 'Không được xóa vai trò admin hoặc manager!'], 403);
        }
        $role->delete();
        return response()->json(['success' => true]);
    });

    // Cập nhật thông tin cá nhân cho user đang đăng nhập
    Route::put('/user', function (Request $request) {
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

    // Room routes
    Route::post('/rooms', [RoomController::class, 'store']);
    Route::get('/rooms/{id}', [RoomController::class, 'show']);
    Route::put('/rooms/{id}', [RoomController::class, 'update']);
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/user/profile', function (Request $request) {
    $user = $request->user()->load('roles');
    return response()->json([
        'data' => [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'name' => $user->name,
            'email' => $user->email,
            'is_verified' => $user->is_verified,
            'roles' => $user->roles
        ]
    ]);
}); 