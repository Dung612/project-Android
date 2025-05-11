<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'device_type_id',
        'status',
        'description',
        'location',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function resourceItems()
    {
        return $this->hasMany(ResourceItem::class);
    }
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'resource_items')
                  ->withPivot('quantity', 'note');
    }
}
