<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $fillable = [
        'room_id',
        'device_id',
        'quantity',
        'note',
    ];
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
     public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
