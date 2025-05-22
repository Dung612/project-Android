<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'room_type_id',
        'location',
        'capacity',
        'status',
        'description',
        'images',
        'open_time',
        'close_time',
        'price'
    ];
    protected $casts = [
        'status' => 'boolean',
        'images' => 'array',
        'open_time' => 'datetime',
        'close_time' => 'datetime',
        'price' => 'decimal:2'
    ];
     public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
     public function resourceItems()
    {
        return $this->hasMany(ResourceItem::class);
    }
     public function devices()
    {
        return $this->belongsToMany(Device::class, 'resource_items')
                  ->withPivot('quantity', 'note');
    }

    public function isAvailable($startTime, $endTime)
    {
        return !$this->bookings()
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })
            ->where('status', 'approved')
            ->exists();
    }
}
