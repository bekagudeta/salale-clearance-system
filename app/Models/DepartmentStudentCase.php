<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentStudentCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'department_id',
        'title',
        'description',
        'status',
        'recorded_by',
        'cleared_by',
        'cleared_at',
    ];

    protected $casts = [
        'cleared_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function clearer()
    {
        return $this->belongsTo(User::class, 'cleared_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
