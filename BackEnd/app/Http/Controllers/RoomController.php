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
} 