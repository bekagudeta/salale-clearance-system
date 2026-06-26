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
        'department_id',
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

    /**
     * The student's academic department — the head/coordinator that gates
     * their clearance request before it reaches service departments.
     */
    public function academicDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function clearances()
    {
        return $this->hasMany(ClearanceRequest::class);
    }

    public function departmentCases()
    {
        return $this->hasMany(DepartmentStudentCase::class);
    }

    public function openCasesForDepartment(int $departmentId)
    {
        return $this->departmentCases()
            ->where('department_id', $departmentId)
            ->open();
    }
}