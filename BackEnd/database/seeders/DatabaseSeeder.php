<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
