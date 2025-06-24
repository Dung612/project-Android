<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'full_name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_verified' => false,
            ]);

            // Assign default role (user) if not already assigned
            $defaultRole = Role::where('name', 'user')->first();
            if ($defaultRole && !$user->roles()->where('role_id', $defaultRole->id)->exists()) {
                $user->roles()->attach($defaultRole->id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tài khoản đã được tạo thành công',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            
            // If user was created but role assignment failed, still return success
            if (str_contains($e->getMessage(), 'Duplicate entry') && str_contains($e->getMessage(), 'user_roles')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tài khoản đã được tạo thành công',
                    'user' => $user ?? null
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo tài khoản: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thông tin tài khoản: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $updateData = [
                'full_name' => $request->name,
                'email' => $request->email,
            ];

            // Only update password if it's provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Tài khoản đã được cập nhật thành công',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật tài khoản: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->roles()->detach(); // Remove role associations
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tài khoản đã được xóa thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa tài khoản: ' . $e->getMessage()
            ], 500);
        }
    }
} 