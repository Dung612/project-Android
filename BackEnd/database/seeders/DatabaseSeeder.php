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
<<<<<<< Updated upstream
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
=======
        // Tắt kiểm tra khóa ngoại để tránh lỗi khi truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('classes')->truncate();
        DB::table('classes')->insert([
            ['id' => 1, 'name' => '62TH-VA', 'code' => '62TH-VA'],
            ['id' => 2, 'name' => '62TH-NB', 'code' => '62TH-NB'],
            ['id' => 3, 'name' => '62HTTT', 'code' => '62HTTT'],
            ['id' => 4, 'name' => '62CK', 'code' => '62CK'],
        ]);
        // Bật lại kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Chạy các seeder theo thứ tự để đảm bảo foreign key constraints
        $this->call([
            // 1. Tạo roles trước
            RoleSeeder::class,
            
            // 2. Tạo users
            UserSeeder::class,
            
            // 3. Tạo room types
            RoomTypeSeeder::class,
            
            // 4. Tạo rooms (cần room_type_id)
            RoomSeeder::class,
            
            // 5. Tạo device types
            DeviceTypeSeeder::class,
            
            // 6. Tạo devices (cần device_type_id)
            DeviceSeeder::class,
            
            // 7. Tạo resource items (cần room_id và device_id)
            ResourceItemSeeder::class,
            
            // 8. Tạo bookings (cần user_id và room_id)
            BookingSeeder::class,
            
            // 9. Tạo booking_device (cần booking_id và device_id)
            BookingDeviceSeeder::class,
            
            // 10. Tạo notifications (cần user_id)
            NotificationSeeder::class,
            
            // 11. Tạo user_roles (cần user_id và role_id)
            UserRoleSeeder::class,
        ]);

        DB::table('departments')->insert([
            ['name' => 'Công nghệ thông tin', 'code' => 'CNTT'],
            ['name' => 'Khoa học máy tính', 'code' => 'KHMT'],
            ['name' => 'Cơ khí', 'code' => 'CK'],
            ['name' => 'Điện tử', 'code' => 'DT'],
        ]);

        DB::table('students')->insert([
            [
                'class_id' => 1,
                'full_name' => 'Nguyễn Văn A',
                'email' => 'sv1@example.com',
                'password' => Hash::make('password123'),
                'is_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 2,
                'full_name' => 'Trần Thị B',
                'email' => 'sv2@example.com',
                'password' => Hash::make('password123'),
                'is_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 3,
                'full_name' => 'Lê Văn C',
                'email' => 'sv3@example.com',
                'password' => Hash::make('password123'),
                'is_verified' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 1,
                'full_name' => 'Phạm Thị D',
                'email' => 'sv4@example.com',
                'password' => Hash::make('password123'),
                'is_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 2,
                'full_name' => 'Vũ Minh E',
                'email' => 'sv5@example.com',
                'password' => Hash::make('password123'),
                'is_verified' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
>>>>>>> Stashed changes
    }
}
