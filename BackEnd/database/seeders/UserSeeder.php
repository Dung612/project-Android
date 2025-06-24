<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'full_name' => 'Admin TLU',
                'email' => 'admin@tlu.edu.vn',
                'password' => Hash::make('12345678'),
                'is_verified' => true
            ],
            [
                'full_name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_verified' => true
            ],
            [
                'full_name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'is_verified' => true
            ],
            [
                'full_name' => 'Approver User',
                'email' => 'approver@example.com',
                'password' => Hash::make('password'),
                'is_verified' => true
            ],
            [
                'full_name' => 'Maintenance User',
                'email' => 'maintenance@example.com',
                'password' => Hash::make('password'),
                'is_verified' => true
            ],
            [
                'full_name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@example.com',
                'password' => Hash::make('password'),
                'is_verified' => true,
                'class_id' => 1
            ],
            [
                'full_name' => 'Trần Thị B',
                'email' => 'tranthib@example.com',
                'password' => Hash::make('password'),
                'is_verified' => true,
                'class_id' => 2
            ],
            [
                'full_name' => 'Lê Văn C',
                'email' => 'levanc@example.com',
                'password' => Hash::make('password'),
                'is_verified' => false,
                'class_id' => 3
            ],
            [
                'full_name' => 'Phạm Thị D',
                'email' => 'phamthid@example.com',
                'password' => Hash::make('password'),
                'is_verified' => true,
                'class_id' => 1
            ],
            [
                'full_name' => 'Giảng viên Nguyễn Văn Giáo',
                'email' => 'teacher1@example.com',
                'password' => Hash::make('password'),
                'is_verified' => true
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
