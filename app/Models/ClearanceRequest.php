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

    /**
     * Approvals that actually gate this request. Approvals belonging to a
     * deactivated department are excluded so a department taken out of the
     * workflow can't permanently block an in-flight clearance.
     */
    public function relevantApprovals()
    {
        $this->loadMissing('approvals.department');

        return $this->approvals->filter(function ($approval) {
            return $approval->department && $approval->department->is_active;
        });
    }

    /**
     * True when every gating (active-department) approval is approved.
     */
    public function hasAllRequiredApprovals(): bool
    {
        $relevant = $this->relevantApprovals();

        return $relevant->isNotEmpty()
            && $relevant->every(fn ($approval) => $approval->status === 'approved');
    }

    public function isFullyApproved()
    {
        return $this->hasAllRequiredApprovals();
    }

    public function updateStatusFromApprovals()
    {
        if ($this->status === 'completed') {
            return;
        }

        $approvals = $this->relevantApprovals();

        if ($approvals->isEmpty()) {
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