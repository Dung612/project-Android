<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
<<<<<<< Updated upstream
        //
=======
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
            ],
            [
                'name' => 'teacher',
                'description' => 'Giảng viên'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
>>>>>>> Stashed changes
    }
}
