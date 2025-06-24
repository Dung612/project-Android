<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roomTypes = [
            [
                'name' => 'Phòng họp nhỏ',
                'description' => 'Phòng họp dành cho nhóm 5-10 người'
            ],
            [
                'name' => 'Phòng họp lớn',
                'description' => 'Phòng họp dành cho nhóm 10-30 người'
            ],
            [
                'name' => 'Phòng thuyết trình',
                'description' => 'Phòng có trang thiết bị thuyết trình chuyên nghiệp'
            ],
            [
                'name' => 'Phòng học',
                'description' => 'Phòng học tập và đào tạo'
            ],
            [
                'name' => 'Phòng làm việc nhóm',
                'description' => 'Phòng dành cho làm việc nhóm và brainstorming'
            ],
            [
                'name' => 'Phòng hội thảo',
                'description' => 'Phòng hội thảo lớn có sức chứa 50+ người'
            ]
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::create($roomType);
        }
    }
}
