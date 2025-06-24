<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        // === BẮT ĐẦU LOGIC XỬ LÝ ẢNH AN TOÀN ===
        
        $imageData = $this->images;
        $decodedImages = [];

        // Kiểm tra xem dữ liệu nhận được có phải là một chuỗi không.
        // Nếu đúng, thử giải mã nó như một chuỗi JSON.
        if (is_string($imageData)) {
            $decodedImages = json_decode($imageData, true);
            // Nếu giải mã thất bại (không phải chuỗi JSON hợp lệ), đặt nó là mảng rỗng để tránh lỗi.
            if (is_null($decodedImages)) {
                $decodedImages = [];
            }
        } 
        // Nếu nó đã là một mảng rồi (do $casts hoạt động đúng), thì dùng luôn.
        elseif (is_array($imageData)) {
            $decodedImages = $imageData;
        }

        // Tạo đường dẫn URL đầy đủ cho mỗi ảnh
        $finalImages = [];
        if (!empty($decodedImages)) {
            foreach ($decodedImages as $image) {
                if (is_string($image) && !empty($image)) {
                    $finalImages[] = url('images/rooms/' . $image);
                }
            }
        }
        // === KẾT THÚC LOGIC XỬ LÝ ẢNH ===

        return [
            'id' => $this->id,
            'name' => $this->name,
            'room_type' => new RoomTypeResource($this->whenLoaded('roomType')),
            'devices' => DeviceResource::collection($this->whenLoaded('devices')),
            'capacity' => $this->capacity,
            'location' => $this->location,
            'description' => $this->description,
            'status' => $this->status,
            'images' => $finalImages, // <-- Sử dụng mảng đã được xử lý
            'open_time' => $this->open_time ? $this->open_time->format('H:i:s') : null,
            'close_time' => $this->close_time ? $this->close_time->format('H:i:s') : null,
            'price' => $this->price,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
            'bookings' => $this->whenLoaded('bookings', function() {
                return $this->bookings->load('user');
            }),
        ];
    }
}