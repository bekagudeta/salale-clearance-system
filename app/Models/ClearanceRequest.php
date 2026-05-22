<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClearanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'reference_no',
        'type',
        'reason',
        'status',
        'requested_date',
        'completed_at',
        'certificate_path',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function approvals()
    {
        return $this->hasMany(ClearanceApproval::class);
    }

    public function isFullyApproved()
    {
        return $this->approvals()->where('status', '!=', 'approved')->count() === 0;
    }

    public function updateStatusFromApprovals()
    {
        $approvals = $this->approvals;
        
        if ($this->status === 'completed') {
            return;
        }
        
        $anyRejected = $approvals->contains('status', 'rejected');
        $allApproved = $approvals->every(fn($a) => $a->status === 'approved');
        $someApproved = $approvals->contains('status', 'approved');
        
        if ($anyRejected) {
            $this->update(['status' => 'rejected']);
        } elseif ($allApproved) {
            $this->update(['status' => 'approved']);
        } elseif ($someApproved) {
            $this->update(['status' => 'in_progress']);
        }
    }
}