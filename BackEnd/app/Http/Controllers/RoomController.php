<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    /**
     * Hiển thị trang danh sách phòng
     */
    public function showRooms()
    {
        $rooms = Room::with('roomType')->paginate(9);
        $roomTypes = RoomType::all();
        return view('rooms', compact('rooms', 'roomTypes'));
    }

    /**
     * Lấy danh sách tất cả phòng
     */
    public function index(Request $request): JsonResponse
    {
        try {
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
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('location', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            }

            $perPage = $request->get('per_page', 10);
            $rooms = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => RoomResource::collection($rooms),
                'meta' => [
                    'total' => $rooms->total(),
                    'per_page' => $rooms->perPage(),
                    'current_page' => $rooms->currentPage(),
                    'last_page' => $rooms->lastPage(),
                    'from' => $rooms->firstItem(),
                    'to' => $rooms->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách phòng',
                'error' => $e->getMessage()
            ], 500);
        }
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_type_id' => 'required|exists:room_types,id',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,maintenance',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0'
        ]);

        $room = Room::create($validated);

        return response()->json([
            'success' => true,
            'data' => new RoomResource($room)
        ]);
    }

    /**
     * Cập nhật thông tin phòng (chỉ admin)
     */
    public function update(Request $request, Room $room): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_type_id' => 'required|exists:room_types,id',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,maintenance',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0'
        ]);

        $room->update($validated);

        return response()->json([
            'success' => true,
            'data' => new RoomResource($room)
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
            'message' => 'Phòng học đã được xóa thành công'
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