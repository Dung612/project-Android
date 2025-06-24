<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    /**
     * Lấy danh sách tất cả phòng
     */
    public function index(Request $request)
    {
        $rooms = Room::with('roomType')->paginate(9);
        $roomTypes = RoomType::all();
        return view('rooms', compact('rooms', 'roomTypes'));
    }

    /**
     * Lấy thông tin chi tiết một phòng
     */
    public function show($id): JsonResponse
    {
        try {
            $room = Room::with('roomType')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => new RoomResource($room)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tìm thấy phòng học'
            ], 404);
        }
    }

    /**
     * Lấy danh sách phòng có sẵn trong khoảng thời gian
     */
    public function available(Request $request): JsonResponse
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $startTime = $request->start_time;
        $endTime = $request->end_time;

        $availableRooms = Room::with(['roomType', 'devices'])
            ->where('status', true)
            ->get()
            ->filter(function ($room) use ($startTime, $endTime) {
                return $room->isAvailable($startTime, $endTime);
            });

        return response()->json([
            'success' => true,
            'data' => RoomResource::collection($availableRooms),
            'message' => 'Lấy danh sách phòng có sẵn thành công'
        ]);
    }

    /**
     * Kiểm tra các tiết học trống trong ngày của một phòng
     */
    public function checkAvailability(Request $request, Room $room): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $date = Carbon::parse($validated['date'])->startOfDay();

        // Định nghĩa 15 tiết học trong ngày
        $periods = [];
        // Tiết 1 bắt đầu lúc 7:00
        $startTime = $date->copy()->setTime(7, 0, 0);

        for ($i = 1; $i <= 15; $i++) {
            $periodEnd = $startTime->copy()->addMinutes(50);
            $periods[] = [
                'period' => $i,
                'start_time' => $startTime->format('H:i'),
                'end_time' => $periodEnd->format('H:i'),
                // Carbon instances for comparison
                'start_datetime' => $startTime->copy(),
                'end_datetime' => $periodEnd->copy(),
            ];

            // Mỗi tiết cách nhau 5 phút
            $startTime->addMinutes(55);
        }

        // Lấy các lịch đặt đã được 'approved' của phòng trong ngày hôm đó
        $bookings = $room->bookings()
            ->where('status', 'approved')
            ->whereDate('start_time', $date->toDateString())
            ->get();

        $availablePeriods = [];

        foreach ($periods as $period) {
            $isBooked = false;
            foreach ($bookings as $booking) {
                $bookingStart = Carbon::parse($booking->start_time);
                $bookingEnd = Carbon::parse($booking->end_time);

                // Điều kiện kiểm tra sự chồng chéo về thời gian
                // (StartA < EndB) and (EndA > StartB)
                if ($period['start_datetime'] < $bookingEnd && $period['end_datetime'] > $bookingStart) {
                    $isBooked = true;
                    break; 
                }
            }

            if (!$isBooked) {
                // Chỉ thêm thông tin cần thiết vào kết quả trả về
                $availablePeriods[] = [
                    'period' => $period['period'],
                    'start_time' => $period['start_time'],
                    'end_time' => $period['end_time'],
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $availablePeriods,
            'message' => 'Lấy danh sách tiết học còn trống thành công'
        ]);
    }

    /**
     * Tạo phòng mới (chỉ admin)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'room_type_id' => 'required|exists:room_types,id',
                'location' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'status' => 'required|numeric|in:0,1',
                'description' => 'nullable|string'
            ]);

            // Convert status to boolean
            $validated['status'] = (bool)$validated['status'];

            \Log::info('Validated data:', $validated);

            $room = Room::create($validated);

            if (!$room) {
                throw new \Exception('Không thể tạo phòng học');
            }

            return response()->json([
                'success' => true,
                'data' => new RoomResource($room->load('roomType')),
                'message' => 'Thêm phòng học thành công'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Room creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm phòng học: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thông tin phòng (chỉ admin)
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $room = Room::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'room_type_id' => 'required|exists:room_types,id',
                'location' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'status' => 'required|numeric|in:0,1',
                'description' => 'nullable|string'
            ]);

            // Convert status to boolean
            $validated['status'] = (bool)$validated['status'];

            $room->update($validated);

            return response()->json([
                'success' => true,
                'data' => new RoomResource($room->fresh()->load('roomType')),
                'message' => 'Cập nhật phòng học thành công'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật phòng học: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa phòng (chỉ admin)
     */
    public function destroy($id): JsonResponse
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Phòng học đã được xóa thành công'
        ]);
    }

    /**
     * Tìm kiếm phòng theo thời gian, thiết bị và sức chứa
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'min_capacity' => 'nullable|integer|min:1',
            'max_capacity' => 'nullable|integer|min:1|gte:min_capacity',
            'device_ids' => 'nullable|array',
            'device_ids.*' => 'exists:devices,id',
            'room_type_id' => 'nullable|exists:room_types,id',
        ]);

        $startTime = $request->start_time;
        $endTime = $request->end_time;

        // Bắt đầu với query cơ bản
        $query = Room::with(['roomType', 'devices'])
            ->where('status', true);

        // Lọc theo sức chứa
        if ($request->filled('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }

        if ($request->filled('max_capacity')) {
            $query->where('capacity', '<=', $request->max_capacity);
        }

        // Lọc theo loại phòng
        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }

        // Lọc theo thiết bị
        if ($request->filled('device_ids')) {
            $deviceIds = $request->device_ids;
            $query->whereHas('devices', function ($q) use ($deviceIds) {
                $q->whereIn('devices.id', $deviceIds);
            });
        }

        // Lấy tất cả phòng thỏa mãn điều kiện filter
        $rooms = $query->get();

        // Lọc theo thời gian có sẵn
        $availableRooms = $rooms->filter(function ($room) use ($startTime, $endTime) {
            return $room->isAvailable($startTime, $endTime);
        });

        return response()->json([
            'success' => true,
            'data' => RoomResource::collection($availableRooms),
            'meta' => [
                'total_rooms' => $availableRooms->count(),
                'search_criteria' => [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'min_capacity' => $request->min_capacity,
                    'max_capacity' => $request->max_capacity,
                    'device_ids' => $request->device_ids,
                    'room_type_id' => $request->room_type_id,
                ]
            ],
            'message' => 'Tìm kiếm phòng thành công'
        ]);
    }
} 