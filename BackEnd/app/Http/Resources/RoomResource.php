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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'room_type' => new RoomTypeResource($this->whenLoaded('roomType')),
            'devices' => DeviceResource::collection($this->whenLoaded('devices')),
            'capacity' => $this->capacity,
            'location' => $this->location,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
            'bookings' => $this->whenLoaded('bookings', function() {
                return $this->bookings->load('user');
            }),
        ];
    }
}