<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'full_name',
        'faculty',
        'department',
        'year',
        'semester',
        'phone',
        'gender',
        'photo',
    ];

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::disk('public')->url($this->photo);
        }

        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clearances()
    {
        return $this->hasMany(ClearanceRequest::class);
    }
}