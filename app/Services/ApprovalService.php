<?php

namespace App\Services;

use App\Models\ClearanceApproval;
use App\Repositories\Interfaces\ClearanceRepositoryInterface;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;

class ApprovalService
{
    use LogsActivity;

    protected $clearanceRepository;

    public function __construct(ClearanceRepositoryInterface $clearanceRepository)
    {
        $this->clearanceRepository = $clearanceRepository;
    }

    public function approve($approvalId, $remarks = null)
    {
        return DB::transaction(function () use ($approvalId, $remarks) {
            $approval = ClearanceApproval::findOrFail($approvalId);
            $approval->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'remarks' => $remarks,
                'approved_at' => now(),
            ]);
            
            $approval->request->updateStatusFromApprovals();
            
            $this->logActivity(
                'approve_clearance',
                'clearance_approvals',
                $approval->id,
                "Approved clearance for department: {$approval->department->name}"
            );
            
            return $approval;
        });
    }

    public function reject($approvalId, $remarks)
    {
        return DB::transaction(function () use ($approvalId, $remarks) {
            $approval = ClearanceApproval::findOrFail($approvalId);
            $approval->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'remarks' => $remarks,
                'approved_at' => now(),
            ]);
            
            $approval->request->updateStatusFromApprovals();
            
            $this->logActivity(
                'reject_clearance',
                'clearance_approvals',
                $approval->id,
                "Rejected clearance for department: {$approval->department->name}. Reason: {$remarks}"
            );
            
            return $approval;
        });
    }

    public function getDepartmentPendingApprovals($departmentId)
    {
        return $this->clearanceRepository->getPendingByDepartment($departmentId);
    }
}