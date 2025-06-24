<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Device;
use Illuminate\Support\Facades\DB;

class BookingDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = Booking::all();
        $devices = Device::all();

        if ($bookings->count() > 0 && $devices->count() > 0) {
            // Tạo booking_device cho một số booking
            foreach ($bookings->random(min(10, $bookings->count())) as $booking) {
                // Chọn ngẫu nhiên 1-3 device cho mỗi booking
                $randomDevices = $devices->random(rand(1, min(3, $devices->count())));
                
                foreach ($randomDevices as $device) {
                    DB::table('booking_device')->insert([
                        'booking_id' => $booking->id,
                        'device_id' => $device->id,
                        'quantity' => rand(1, 3),
                        'note' => 'Yêu cầu thiết bị cho booking #' . $booking->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
} 