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
        'status'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Scope a query to only include active resource items.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include resource items with quantity greater than zero.
     */
    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }
}
