<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Quản trị viên hệ thống'
            ],
            [
                'name' => 'manager',
                'description' => 'Quản lý phòng ban'
            ],
            [
                'name' => 'user',
                'description' => 'Người dùng thông thường'
            ],
            [
                'name' => 'approver',
                'description' => 'Người phê duyệt booking'
            ],
            [
                'name' => 'maintenance',
                'description' => 'Nhân viên bảo trì'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
