<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() > 0) {
            $messages = [
                'Booking của bạn đã được phê duyệt',
                'Booking của bạn đã bị từ chối',
                'Có booking mới cần phê duyệt',
                'Thiết bị đã được bảo trì xong',
                'Phòng đã được dọn dẹp sạch sẽ',
                'Nhắc nhở: Booking của bạn sẽ bắt đầu trong 30 phút',
                'Booking của bạn đã được hủy',
                'Có thông báo mới từ hệ thống',
                'Yêu cầu booking thiết bị bổ sung',
                'Thông báo bảo trì hệ thống'
            ];

            foreach ($users as $user) {
                // Tạo 3-8 thông báo cho mỗi user
                $notificationCount = rand(3, 8);
                
                for ($i = 0; $i < $notificationCount; $i++) {
                    Notification::create([
                        'user_id' => $user->id,
                        'message' => $messages[array_rand($messages)],
                        'is_read' => rand(0, 1), // 0: chưa đọc, 1: đã đọc
                        'created_at' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                    ]);
                }
            }
        }
    }
} 