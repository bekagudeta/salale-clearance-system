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
        'officer_user_id',
        'priority_order',
        'is_active',
    ];

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_user_id');
    }

    public function approvals()
    {
        return $this->hasMany(ClearanceApproval::class);
    }

    public function staff()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('position', 'can_approve')
            ->withTimestamps();
    }

    public function allStaff()
    {
        return $this->staff()->where('can_approve', true);
    }
}