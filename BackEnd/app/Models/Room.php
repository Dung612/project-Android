<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'description'
    ];
    protected $casts = [
        'capacity' => 'integer',
        'status' => 'boolean'
    ];
     public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
     public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
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
