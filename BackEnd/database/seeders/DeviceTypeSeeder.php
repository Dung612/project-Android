<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeviceType;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deviceTypes = [
            [
                'name' => 'Máy chiếu',
                'description' => 'Thiết bị chiếu hình ảnh lên màn hình'
            ],
            [
                'name' => 'Loa',
                'description' => 'Hệ thống âm thanh'
            ],
            [
                'name' => 'Microphone',
                'description' => 'Microphone không dây và có dây'
            ],
            [
                'name' => 'Màn hình',
                'description' => 'Màn hình LCD/LED để hiển thị'
            ],
            [
                'name' => 'Bảng trắng',
                'description' => 'Bảng trắng để viết và vẽ'
            ],
            [
                'name' => 'Máy tính',
                'description' => 'Máy tính để bàn hoặc laptop'
            ],
            [
                'name' => 'Camera',
                'description' => 'Camera ghi hình và livestream'
            ],
            [
                'name' => 'Điều hòa',
                'description' => 'Hệ thống điều hòa không khí'
            ],
            [
                'name' => 'Quạt',
                'description' => 'Quạt làm mát'
            ],
            [
                'name' => 'Bàn ghế',
                'description' => 'Bàn ghế làm việc và họp'
            ]
        ];

        foreach ($deviceTypes as $deviceType) {
            DeviceType::create($deviceType);
        }
    }
}
