<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\DeviceType;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deviceTypes = DeviceType::all();

        if ($deviceTypes->count() > 0) {
            $devices = [
                [
                    'name' => 'Máy chiếu Epson EB-X41',
                    'device_type_id' => $deviceTypes->where('name', 'Máy chiếu')->first()->id,
                    'status' => true,
                    'description' => 'Máy chiếu độ phân giải cao, phù hợp cho thuyết trình',
                    'location' => 'Kho thiết bị A'
                ],
                [
                    'name' => 'Máy chiếu BenQ TH685P',
                    'device_type_id' => $deviceTypes->where('name', 'Máy chiếu')->first()->id,
                    'status' => true,
                    'description' => 'Máy chiếu gaming và thuyết trình',
                    'location' => 'Kho thiết bị B'
                ],
                [
                    'name' => 'Loa JBL Professional',
                    'device_type_id' => $deviceTypes->where('name', 'Loa')->first()->id,
                    'status' => true,
                    'description' => 'Hệ thống loa chuyên nghiệp cho hội thảo',
                    'location' => 'Kho thiết bị A'
                ],
                [
                    'name' => 'Loa Bose L1 Compact',
                    'device_type_id' => $deviceTypes->where('name', 'Loa')->first()->id,
                    'status' => true,
                    'description' => 'Loa di động chất lượng cao',
                    'location' => 'Kho thiết bị B'
                ],
                [
                    'name' => 'Microphone Shure SM58',
                    'device_type_id' => $deviceTypes->where('name', 'Microphone')->first()->id,
                    'status' => true,
                    'description' => 'Microphone có dây chuyên nghiệp',
                    'location' => 'Kho thiết bị A'
                ],
                [
                    'name' => 'Microphone Sennheiser XSW 1-ME2',
                    'device_type_id' => $deviceTypes->where('name', 'Microphone')->first()->id,
                    'status' => true,
                    'description' => 'Microphone không dây',
                    'location' => 'Kho thiết bị B'
                ],
                [
                    'name' => 'Màn hình Samsung 65"',
                    'device_type_id' => $deviceTypes->where('name', 'Màn hình')->first()->id,
                    'status' => true,
                    'description' => 'Màn hình LED 4K cho thuyết trình',
                    'location' => 'Kho thiết bị A'
                ],
                [
                    'name' => 'Màn hình LG 55"',
                    'device_type_id' => $deviceTypes->where('name', 'Màn hình')->first()->id,
                    'status' => true,
                    'description' => 'Màn hình LED Full HD',
                    'location' => 'Kho thiết bị B'
                ],
                [
                    'name' => 'Bảng trắng từ tính',
                    'device_type_id' => $deviceTypes->where('name', 'Bảng trắng')->first()->id,
                    'status' => true,
                    'description' => 'Bảng trắng có thể gắn nam châm',
                    'location' => 'Kho thiết bị A'
                ],
                [
                    'name' => 'Bảng trắng thường',
                    'device_type_id' => $deviceTypes->where('name', 'Bảng trắng')->first()->id,
                    'status' => true,
                    'description' => 'Bảng trắng cơ bản',
                    'location' => 'Kho thiết bị B'
                ],
                [
                    'name' => 'Laptop Dell Latitude',
                    'device_type_id' => $deviceTypes->where('name', 'Máy tính')->first()->id,
                    'status' => true,
                    'description' => 'Laptop doanh nghiệp',
                    'location' => 'Kho thiết bị A'
                ],
                [
                    'name' => 'PC HP ProDesk',
                    'device_type_id' => $deviceTypes->where('name', 'Máy tính')->first()->id,
                    'status' => true,
                    'description' => 'Máy tính để bàn văn phòng',
                    'location' => 'Kho thiết bị B'
                ],
                [
                    'name' => 'Camera Logitech C920',
                    'device_type_id' => $deviceTypes->where('name', 'Camera')->first()->id,
                    'status' => true,
                    'description' => 'Webcam HD cho họp trực tuyến',
                    'location' => 'Kho thiết bị A'
                ],
                [
                    'name' => 'Camera Sony HDR-CX405',
                    'device_type_id' => $deviceTypes->where('name', 'Camera')->first()->id,
                    'status' => true,
                    'description' => 'Camera quay phim chuyên nghiệp',
                    'location' => 'Kho thiết bị B'
                ],
                [
                    'name' => 'Điều hòa Daikin',
                    'device_type_id' => $deviceTypes->where('name', 'Điều hòa')->first()->id,
                    'status' => true,
                    'description' => 'Điều hòa inverter tiết kiệm điện',
                    'location' => 'Phòng kỹ thuật'
                ],
                [
                    'name' => 'Quạt đứng Panasonic',
                    'device_type_id' => $deviceTypes->where('name', 'Quạt')->first()->id,
                    'status' => true,
                    'description' => 'Quạt đứng 3 cánh',
                    'location' => 'Kho thiết bị A'
                ],
                [
                    'name' => 'Bàn họp 8 chỗ',
                    'device_type_id' => $deviceTypes->where('name', 'Bàn ghế')->first()->id,
                    'status' => true,
                    'description' => 'Bàn họp hình tròn 8 chỗ ngồi',
                    'location' => 'Kho thiết bị A'
                ],
                [
                    'name' => 'Ghế văn phòng',
                    'device_type_id' => $deviceTypes->where('name', 'Bàn ghế')->first()->id,
                    'status' => true,
                    'description' => 'Ghế văn phòng có lưng tựa',
                    'location' => 'Kho thiết bị B'
                ],
                [
                    'name' => 'Máy chiếu cũ',
                    'device_type_id' => $deviceTypes->where('name', 'Máy chiếu')->first()->id,
                    'status' => false,
                    'description' => 'Máy chiếu đang bảo trì',
                    'location' => 'Kho bảo trì'
                ],
                [
                    'name' => 'Loa hỏng',
                    'device_type_id' => $deviceTypes->where('name', 'Loa')->first()->id,
                    'status' => false,
                    'description' => 'Loa đang sửa chữa',
                    'location' => 'Kho bảo trì'
                ]
            ];

            foreach ($devices as $device) {
                Device::create($device);
            }
        }
    }
}
