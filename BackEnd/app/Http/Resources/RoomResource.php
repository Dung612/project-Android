<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  mixed  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'room_type_id' => $this->room_type_id,
            'room_type' => new RoomTypeResource($this->whenLoaded('roomType')),
            'devices' => DeviceResource::collection($this->whenLoaded('devices')),
            'capacity' => $this->capacity,
            'location' => $this->location,
            'status' => $this->status,
            'description' => $this->description,
            'bookings' => $this->whenLoaded('bookings', function() {
                return $this->bookings->load('user');
            })
        ];
    }
}