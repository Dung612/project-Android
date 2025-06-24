<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Nhận và lưu đơn booking mới
     */
    public function store(BookingRequest $request): JsonResponse
    {
        $data = [
            'user_id'    => $request->user()->id, // Lấy user ID từ token đã xác thực
            'room_id'    => $request->room_id,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'purpose'    => $request->purpose,
            'note'       => $request->note,
            'status'     => 'pending',
            'approved_by' => null,
            'rejection_reason' => null,
        ];

        $booking = Booking::create($data);

        // Nếu có gửi kèm danh sách thiết bị thì lưu vào bảng booking_device
        if ($request->has('devices')) {
            $devicesToAttach = [];
            foreach ($request->input('devices', []) as $device) {
                // Key là device_id, value là các trường trong bảng pivot
                $devicesToAttach[$device['device_id']] = [
                    'quantity' => $device['quantity'] ?? 1, // Mặc định là 1 nếu không có
                    'note'     => $device['note'] ?? null,
                ];
            }
            if (!empty($devicesToAttach)) {
                $booking->devices()->attach($devicesToAttach);
            }
        }

        $booking->load(['user', 'room', 'devices']);

        return response()->json([
            'success' => true,
            'data'    => new BookingResource($booking),
            'message' => 'Đặt phòng thành công, vui lòng chờ phê duyệt.'
        ], 201);
    }

    /**
     * Lấy lịch sử đặt phòng của người dùng hiện tại
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = $user->bookings()->with(['room', 'devices'])->orderBy('start_time', 'desc');

        // Lọc theo trạng thái nếu có truyền status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => BookingResource::collection($bookings),
            'meta'    => [
                'current_page' => $bookings->currentPage(),
                'last_page'    => $bookings->lastPage(),
                'per_page'     => $bookings->perPage(),
                'total'        => $bookings->total(),
            ],
            'message' => 'Lấy lịch sử đặt phòng thành công.'
        ]);
    }
}