<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $rooms = Room::all();

        if ($users->count() > 0 && $rooms->count() > 0) {
            // Tạo các booking mẫu
            $purposes = [
                'Họp nhóm dự án',
                'Thuyết trình',
                'Học tập',
                'Làm việc nhóm',
                'Đào tạo',
                'Hội thảo',
                'Phỏng vấn',
                'Luyện tập thuyết trình'
            ];

            $statuses = ['pending', 'approved', 'rejected', 'cancelled'];

            for ($i = 0; $i < 20; $i++) {
                $startTime = Carbon::now()->addDays(rand(1, 30))->setHour(rand(8, 18))->setMinute(0);
                $endTime = $startTime->copy()->addHours(rand(1, 4));

                $booking = Booking::create([
                    'user_id' => $users->random()->id,
                    'room_id' => $rooms->random()->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'purpose' => $purposes[array_rand($purposes)],
                    'note' => 'Ghi chú cho booking mẫu #' . ($i + 1),
                    'status' => $statuses[array_rand($statuses)],
                    'approved_by' => $statuses[array_rand($statuses)] === 'approved' ? $users->random()->id : null,
                    'rejection_reason' => $statuses[array_rand($statuses)] === 'rejected' ? 'Lý do từ chối mẫu' : null,
                ]);
            }
        }
    }
} 