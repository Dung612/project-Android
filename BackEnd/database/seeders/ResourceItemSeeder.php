<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResourceItem;
use App\Models\Room;
use App\Models\Device;

class ResourceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách room và device để tạo resource items
        $rooms = Room::all();
        $devices = Device::all();

        if ($rooms->count() > 0 && $devices->count() > 0) {
            // Tạo resource items cho mỗi room với một số device
            foreach ($rooms as $room) {
                // Chọn ngẫu nhiên 2-4 device cho mỗi room
                $randomDevices = $devices->random(rand(2, min(4, $devices->count())));
                
                foreach ($randomDevices as $device) {
                    ResourceItem::create([
                        'room_id' => $room->id,
                        'device_id' => $device->id,
                        'quantity' => rand(1, 5),
                        'note' => 'Thiết bị được cung cấp cho phòng ' . $room->name,
                        'status' => rand(0, 1), // 0: không hoạt động, 1: hoạt động
                    ]);
                }
            }
        }
    }
} 