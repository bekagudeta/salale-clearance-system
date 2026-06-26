<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'officer_user_id',
        'priority_order',
        'is_active',
    ];

    /**
     * Academic departments (Software Engineering, etc.) act as the first-stage
     * head/coordinator gate for their own students.
     */
    public function scopeAcademic($query)
    {
        return $query->where('category', 'academic');
    }

    /**
     * Service departments (clinic, housing, store, ...) open only after the
     * student's academic head approves.
     */
    public function scopeService($query)
    {
        return $query->where('category', 'service');
    }

    public function isAcademic(): bool
    {
        return $this->category === 'academic';
    }

    /**
     * The head/coordinator responsible for approving on behalf of this
     * department: the assigned officer, otherwise the staff member flagged
     * with position 'head'.
     */
    public function head()
    {
        if ($this->officer) {
            return $this->officer;
        }

        return $this->staff()->wherePivot('position', 'head')->first();
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_user_id');
    }

    public function approvals()
    {
        return $this->hasMany(ClearanceApproval::class);
    }

    public function studentCases()
    {
        return $this->hasMany(DepartmentStudentCase::class);
    }

    public function staff()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('position', 'can_approve')
            ->withTimestamps();
    }

    public function allStaff()
    {
        return $this->staff()->wherePivot('can_approve', true)->get();
    }
}
