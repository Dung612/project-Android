<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Tắt kiểm tra khóa ngoại để tránh lỗi khi truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Xóa dữ liệu cũ
        DB::table('users')->truncate();
        DB::table('classes')->truncate();
        
        // Tạo lớp học
        DB::table('classes')->insert([
            ['id' => 1, 'name' => '62TH-VA', 'code' => '62TH-VA'],
            ['id' => 2, 'name' => '62TH-NB', 'code' => '62TH-NB'],
            ['id' => 3, 'name' => '62HTTT', 'code' => '62HTTT'],
            ['id' => 4, 'name' => '62CK', 'code' => '62CK'],
        ]);
        
        // Bật lại kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Chạy các seeder theo thứ tự
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            RoomTypeSeeder::class,
            RoomSeeder::class,
            DeviceTypeSeeder::class,
            DeviceSeeder::class,
            UserRoleSeeder::class,
        ]);
    }
}
