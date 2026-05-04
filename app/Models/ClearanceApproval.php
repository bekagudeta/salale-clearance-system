<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClearanceApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'clearance_request_id',
        'department_id',
        'approved_by',
        'status',
        'remarks',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(ClearanceRequest::class, 'clearance_request_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}