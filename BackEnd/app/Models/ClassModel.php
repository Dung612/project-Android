<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class ClassModel extends Model
{
    use HasFactory;
    protected $table = 'classes';
    protected $fillable = [
        'name',
        'description',
    ];
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
} 