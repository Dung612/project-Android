<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
            $query->where('status', $request->status);
        }

        // Lọc theo loại phòng
        if ($request->has('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }

        // Lọc theo sức chứa
        if ($request->has('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }

        // Tìm kiếm theo tên phòng
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $rooms = $query->get(); // Hiển thị tất cả phòng

        return response()->json([
            'data' => RoomResource::collection($rooms),
            'meta' => [
                'total' => $rooms->count(),
                'per_page' => $rooms->count(),
                'current_page' => 1,
                'last_page' => 1
            ]
        ]);
    }

    /**
     * Lấy thông tin chi tiết một phòng
     */
    public function show(Room $room): JsonResponse
    {
        $room->load(['roomType', 'devices', 'bookings.user']);
        return response()->json([
            'success' => true,
            'data' => new \App\Http\Resources\RoomResource($room),
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
            'status' => 'required|integer|in:0,1,2',
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
            'status' => 'sometimes|integer|in:0,1,2',
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
     * API trả về số liệu thống kê cho dashboard
     */
    public function dashboardStats(Request $request): JsonResponse
    {
        $totalRooms = \App\Models\Room::count();
        $emptyRooms = \App\Models\Room::where('status', 1)->count();
        $maintenanceRooms = \App\Models\Room::where('status', 0)->count();
        $waitingList = \App\Models\Room::where('status', 2)->count();

        return response()->json([
            'total_rooms' => $totalRooms,
            'empty_rooms' => $emptyRooms,
            'maintenance_rooms' => $maintenanceRooms,
            'waiting_list' => $waitingList,
        ]);
    }
} 