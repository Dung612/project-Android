<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use Carbon\Carbon;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rooms = [
            [
                'name' => 'Phòng 101',
                'room_type_id' => 1,
                'location' => 'Tầng 1, Nhà A1',
                'capacity' => 40,
                'status' => 'available',
                'description' => 'Phòng học lý thuyết cơ bản',
                'open_time' => '07:00:00',
                'close_time' => '17:30:00',
                'price' => 0
            ],
            [
                'name' => 'Phòng 102',
                'room_type_id' => 1,
                'location' => 'Tầng 1, Nhà A1',
                'capacity' => 35,
                'status' => 'available',
                'description' => 'Phòng học lý thuyết cơ bản',
                'open_time' => '07:00:00',
                'close_time' => '17:30:00',
                'price' => 0
            ],
            [
                'name' => 'Phòng Lab 201',
                'room_type_id' => 2,
                'location' => 'Tầng 2, Nhà A1',
                'capacity' => 30,
                'status' => 'available',
                'description' => 'Phòng thực hành máy tính',
                'open_time' => '07:00:00',
                'close_time' => '17:30:00',
                'price' => 0
            ],
            [
                'name' => 'Phòng Lab 202',
                'room_type_id' => 2,
                'location' => 'Tầng 2, Nhà A1',
                'capacity' => 30,
                'status' => 'maintenance',
                'description' => 'Phòng thực hành máy tính',
                'open_time' => '07:00:00',
                'close_time' => '17:30:00',
                'price' => 0
            ],
            [
                'name' => 'Hội trường A',
                'room_type_id' => 3,
                'location' => 'Tầng 1, Nhà B1',
                'capacity' => 200,
                'status' => 'available',
                'description' => 'Hội trường lớn cho các sự kiện',
                'open_time' => '07:00:00',
                'close_time' => '21:00:00',
                'price' => 1000000
            ],
            [
                'name' => 'Phòng họp 301',
                'room_type_id' => 4,
                'location' => 'Tầng 3, Nhà A1',
                'capacity' => 20,
                'status' => 'available',
                'description' => 'Phòng họp nhỏ cho các cuộc họp nội bộ',
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'price' => 200000
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
