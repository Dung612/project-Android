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
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Xử lý images để trả về đường dẫn đầy đủ
        $images = [];
        if ($this->images) {
            // Kiểm tra xem images có phải là array không
            if (is_array($this->images)) {
                foreach ($this->images as $image) {
                    $images[] = url('images/rooms/' . $image);
                }
            } elseif (is_string($this->images)) {
                // Nếu là string, thử decode JSON
                $decoded = json_decode($this->images, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $image) {
                        $images[] = url('images/rooms/' . $image);
                    }
                } else {
                    // Nếu không phải JSON, coi như là 1 ảnh
                    $images[] = url('images/rooms/' . $this->images);
                }
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'room_type' => new RoomTypeResource($this->whenLoaded('roomType')),
            'devices' => DeviceResource::collection($this->whenLoaded('devices')),
            'capacity' => $this->capacity,
            'location' => $this->location,
            'description' => $this->description,
            'status' => $this->status,
            'images' => $images, // Thêm trường images với đường dẫn đầy đủ
            'open_time' => $this->open_time ? $this->open_time->format('H:i:s') : null,
            'close_time' => $this->close_time ? $this->close_time->format('H:i:s') : null,
            'price' => $this->price,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}