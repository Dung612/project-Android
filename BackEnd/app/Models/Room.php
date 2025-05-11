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
    ];
    protected $casts = [
        'status' => 'boolean',
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
}
