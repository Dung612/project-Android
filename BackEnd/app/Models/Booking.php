<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'room_id',
        'device_id',
        'start_time',
        'end_time',
        'purpose',
        'note',
        'status',
    ];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
