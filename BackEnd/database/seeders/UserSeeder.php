<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
                'is_verified' => true
            ],
            [
                'full_name' => 'Trần Thị B',
                'email' => 'tranthib@example.com',
                'password' => Hash::make('password'),
                'is_verified' => true
            ],
            [
                'full_name' => 'Lê Văn C',
                'email' => 'levanc@example.com',
                'password' => Hash::make('password'),
                'is_verified' => false
            ],
            [
                'full_name' => 'Phạm Thị D',
                'email' => 'phamthid@example.com',
                'password' => Hash::make('password'),
                'is_verified' => true
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
