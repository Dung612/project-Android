<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class RoomController extends Controller
{
    /**
     * Lấy danh sách tất cả phòng
     */
    public function index(Request $request): JsonResponse
    {
        $query = Room::with(['roomType', 'devices']);

        // Lọc theo trạng thái
        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        }

        // Lọc theo loại phòng
        if ($request->has('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }

        // Lọc theo sức chứa
        if ($request->has('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }

        $rooms = $query->get();

        return response()->json([
            'success' => true,
            'data' => RoomResource::collection($rooms),
            'message' => 'Lấy danh sách phòng thành công'
        ]);
    }

    /**
     * Lấy thông tin chi tiết một phòng
     */
    public function show(Room $room): JsonResponse
    {
        $room->load(['roomType', 'devices']);

        return response()->json([
            'success' => true,
            'data' => new RoomResource($room),
            'message' => 'Lấy thông tin phòng thành công'
        ]);
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
        $request->validate([
            'name' => 'required|string|max:255',
            'room_type_id' => 'required|exists:room_types,id',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'string',
            'open_time' => 'nullable|date_format:H:i:s',
            'close_time' => 'nullable|date_format:H:i:s',
            'price' => 'nullable|numeric|min:0',
        ]);

        $room = Room::create($request->all());

        return response()->json([
            'success' => true,
            'data' => new RoomResource($room),
            'message' => 'Tạo phòng thành công'
        ], 201);
    }

    /**
     * Cập nhật thông tin phòng (chỉ admin)
     */
    public function update(Request $request, Room $room): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'room_type_id' => 'sometimes|required|exists:room_types,id',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'string',
            'open_time' => 'nullable|date_format:H:i:s',
            'close_time' => 'nullable|date_format:H:i:s',
            'price' => 'nullable|numeric|min:0',
            'status' => 'sometimes|boolean',
        ]);

        $room->update($request->all());

        return response()->json([
            'success' => true,
            'data' => new RoomResource($room),
            'message' => 'Cập nhật phòng thành công'
        ]);
    }

    /**
     * Xóa phòng (chỉ admin)
     */
    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa phòng thành công'
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