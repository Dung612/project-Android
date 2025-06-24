<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role && !$this->hasRole($roleName)) {
            $this->roles()->attach($role->id);
        }
        return $this;
    }

    public function removeRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $this->roles()->detach($role->id);
        }
        return $this;
    }

    public function isApprover()
    {
        return $this->hasRole('approver');
    }

    public function class()
    {
        return $this->belongsTo(\App\Models\ClassModel::class, 'class_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->assignRole('user');
        });

        static::deleting(function ($user) {
            $user->bookings()->delete();
            $user->notifications()->delete();
            $user->roles()->detach();
        });
    }
}