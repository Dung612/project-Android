<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $roles = Role::all();

        if ($users->count() > 0 && $roles->count() > 0) {
            // Gán role cho tất cả users
            foreach ($users as $user) {
                // Mỗi user có thể có 1-2 roles
                $userRoles = $roles->random(rand(1, min(2, $roles->count())));
                
                foreach ($userRoles as $role) {
                    // Kiểm tra xem user đã có role này chưa
                    $exists = DB::table('user_roles')
                        ->where('user_id', $user->id)
                        ->where('role_id', $role->id)
                        ->exists();
                    
                    if (!$exists) {
                        DB::table('user_roles')->insert([
                            'user_id' => $user->id,
                            'role_id' => $role->id,
                        ]);
                    }
                }
            }

            $teacherRole = $roles->where('name', 'teacher')->first();
            $teacherUser = $users->where('email', 'teacher1@example.com')->first();
            if ($teacherRole && $teacherUser) {
                $exists = DB::table('user_roles')
                    ->where('user_id', $teacherUser->id)
                    ->where('role_id', $teacherRole->id)
                    ->exists();
                if (!$exists) {
                    DB::table('user_roles')->insert([
                        'user_id' => $teacherUser->id,
                        'role_id' => $teacherRole->id,
                    ]);
                }
            }

            $adminUser = $users->where('email', 'admin@tlu.edu.vn')->first();
            $adminRole = $roles->where('name', 'admin')->first();
            if ($adminUser && $adminRole) {
                $exists = DB::table('user_roles')
                    ->where('user_id', $adminUser->id)
                    ->where('role_id', $adminRole->id)
                    ->exists();
                if (!$exists) {
                    DB::table('user_roles')->insert([
                        'user_id' => $adminUser->id,
                        'role_id' => $adminRole->id,
                    ]);
                }
            }
        }
    }
} 