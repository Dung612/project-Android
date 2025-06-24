<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\RoomType;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roomTypes = RoomType::all();

        if ($roomTypes->count() > 0) {
            $rooms = [
                [
                    'name' => 'Phòng A101',
                    'room_type_id' => $roomTypes->where('name', 'Phòng họp nhỏ')->first()->id,
                    'location' => 'Tầng 1 - Khu A',
                    'capacity' => 8,
                    'status' => true,
                    'description' => 'Phòng họp nhỏ với bàn tròn và ghế thoải mái',
                    'images' => json_encode(['room_a101_1.jpg', 'room_a101_2.jpg']),
                    'open_time' => '08:00:00',
                    'close_time' => '22:00:00',
                    'price' => 100000
                ],
                [
                    'name' => 'Phòng A102',
                    'room_type_id' => $roomTypes->where('name', 'Phòng họp nhỏ')->first()->id,
                    'location' => 'Tầng 1 - Khu A',
                    'capacity' => 10,
                    'status' => true,
                    'description' => 'Phòng họp nhỏ với bàn hình chữ nhật',
                    'images' => json_encode(['room_a102_1.jpg']),
                    'open_time' => '08:00:00',
                    'close_time' => '22:00:00',
                    'price' => 120000
                ],
                [
                    'name' => 'Phòng B201',
                    'room_type_id' => $roomTypes->where('name', 'Phòng họp lớn')->first()->id,
                    'location' => 'Tầng 2 - Khu B',
                    'capacity' => 20,
                    'status' => true,
                    'description' => 'Phòng họp lớn với hệ thống âm thanh',
                    'images' => json_encode(['room_b201_1.jpg', 'room_b201_2.jpg']),
                    'open_time' => '08:00:00',
                    'close_time' => '22:00:00',
                    'price' => 200000
                ],
                [
                    'name' => 'Phòng C301',
                    'room_type_id' => $roomTypes->where('name', 'Phòng thuyết trình')->first()->id,
                    'location' => 'Tầng 3 - Khu C',
                    'capacity' => 50,
                    'status' => true,
                    'description' => 'Phòng thuyết trình với máy chiếu và âm thanh chuyên nghiệp',
                    'images' => json_encode(['room_c301_1.jpg', 'room_c301_2.jpg', 'room_c301_3.jpg']),
                    'open_time' => '08:00:00',
                    'close_time' => '22:00:00',
                    'price' => 300000
                ],
                [
                    'name' => 'Phòng D401',
                    'room_type_id' => $roomTypes->where('name', 'Phòng học')->first()->id,
                    'location' => 'Tầng 4 - Khu D',
                    'capacity' => 30,
                    'status' => true,
                    'description' => 'Phòng học với bảng trắng và máy chiếu',
                    'images' => json_encode(['room_d401_1.jpg']),
                    'open_time' => '08:00:00',
                    'close_time' => '22:00:00',
                    'price' => 150000
                ],
                [
                    'name' => 'Phòng E501',
                    'room_type_id' => $roomTypes->where('name', 'Phòng làm việc nhóm')->first()->id,
                    'location' => 'Tầng 5 - Khu E',
                    'capacity' => 15,
                    'status' => true,
                    'description' => 'Phòng làm việc nhóm với bàn làm việc linh hoạt',
                    'images' => json_encode(['room_e501_1.jpg', 'room_e501_2.jpg']),
                    'open_time' => '08:00:00',
                    'close_time' => '22:00:00',
                    'price' => 180000
                ],
                [
                    'name' => 'Phòng F601',
                    'room_type_id' => $roomTypes->where('name', 'Phòng hội thảo')->first()->id,
                    'location' => 'Tầng 6 - Khu F',
                    'capacity' => 100,
                    'status' => true,
                    'description' => 'Phòng hội thảo lớn với sân khấu và hệ thống âm thanh',
                    'images' => json_encode(['room_f601_1.jpg', 'room_f601_2.jpg']),
                    'open_time' => '08:00:00',
                    'close_time' => '22:00:00',
                    'price' => 500000
                ],
                [
                    'name' => 'Phòng A103',
                    'room_type_id' => $roomTypes->where('name', 'Phòng họp nhỏ')->first()->id,
                    'location' => 'Tầng 1 - Khu A',
                    'capacity' => 6,
                    'status' => false,
                    'description' => 'Phòng đang bảo trì',
                    'images' => json_encode(['room_a103_1.jpg']),
                    'open_time' => '08:00:00',
                    'close_time' => '22:00:00',
                    'price' => 80000
                ]
            ];

            foreach ($rooms as $room) {
                Room::create($room);
            }
        }
    }
}
