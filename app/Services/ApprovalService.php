<?php

namespace App\Services;

use App\Models\ClearanceApproval;
use App\Repositories\Interfaces\ClearanceRepositoryInterface;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use App\Events\ClearanceApproved;
use App\Events\ClearanceRejected;

class ApprovalService
{
    use LogsActivity;

    protected $clearanceRepository;
    protected $notificationService;

    public function __construct(
        ClearanceRepositoryInterface $clearanceRepository,
        NotificationService $notificationService
    ) {
        $this->clearanceRepository = $clearanceRepository;
        $this->notificationService = $notificationService;
    }

    /**
     * Approve a clearance request
     */
    public function approve($approvalId, $remarks = null)
    {
        return DB::transaction(function () use ($approvalId, $remarks) {
            $approval = ClearanceApproval::with(['request.student.user', 'department'])->findOrFail($approvalId);

            if ($approval->department->officer_user_id !== auth()->id()) {
                throw new \Exception('You are not authorized to process this approval.');
            }
            
            // Check if already approved or rejected
            if ($approval->status !== 'pending') {
                throw new \Exception('This approval has already been processed.');
            }
            
            $approval->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'remarks' => $remarks,
                'approved_at' => now(),
            ]);
            
            // Update main request status
            $approval->request->updateStatusFromApprovals();
            
            // Log activity
            $this->logActivity(
                'approve_clearance',
                'clearance_approvals',
                $approval->id,
                "Approved clearance for department: {$approval->department->name}"
            );
            
            // Dispatch event
            Event::dispatch(new ClearanceApproved($approval));
            
            return $approval;
        });
    }

    /**
     * Reject a clearance request
     */
    public function reject($approvalId, $remarks)
    {
        return DB::transaction(function () use ($approvalId, $remarks) {
            $approval = ClearanceApproval::with(['request.student.user', 'department'])->findOrFail($approvalId);

            if ($approval->department->officer_user_id !== auth()->id()) {
                throw new \Exception('You are not authorized to process this approval.');
            }
            
            // Check if already approved or rejected
            if ($approval->status !== 'pending') {
                throw new \Exception('This approval has already been processed.');
            }
            
            $approval->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'remarks' => $remarks,
                'approved_at' => now(),
            ]);
            
            // Update main request status
            $approval->request->updateStatusFromApprovals();
            
            // Log activity
            $this->logActivity(
                'reject_clearance',
                'clearance_approvals',
                $approval->id,
                "Rejected clearance for department: {$approval->department->name}. Reason: {$remarks}"
            );
            
            // Dispatch event
            Event::dispatch(new ClearanceRejected($approval));
            
            return $approval;
        });
    }

    /**
     * Get pending approvals for a department
     */
    public function getDepartmentPendingApprovals($departmentId)
    {
        return ClearanceApproval::where('department_id', $departmentId)
            ->where('status', 'pending')
            ->with(['request.student'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get approval history for a department
     */
    public function getDepartmentHistory($departmentId, array $filters = [])
    {
        $query = ClearanceApproval::where('department_id', $departmentId)
            ->with(['request.student', 'officer']);
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['from_date'])) {
            $query->whereDate('approved_at', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->whereDate('approved_at', '<=', $filters['to_date']);
        }
        
        return $query->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 20);
    }

    /**
     * Get approval statistics for a department
     */
    public function getDepartmentStats($departmentId)
    {
        return [
            'total' => ClearanceApproval::where('department_id', $departmentId)->count(),
            'pending' => ClearanceApproval::where('department_id', $departmentId)
                ->where('status', 'pending')
                ->count(),
            'approved' => ClearanceApproval::where('department_id', $departmentId)
                ->where('status', 'approved')
                ->count(),
            'rejected' => ClearanceApproval::where('department_id', $departmentId)
                ->where('status', 'rejected')
                ->count(),
            'approved_today' => ClearanceApproval::where('department_id', $departmentId)
                ->whereDate('approved_at', today())
                ->where('status', 'approved')
                ->count(),
            'average_processing_time' => $this->getAverageProcessingTime($departmentId),
        ];
    }

    /**
     * Get average processing time in hours
     */
    private function getAverageProcessingTime($departmentId)
    {
        $avgTime = ClearanceApproval::where('department_id', $departmentId)
            ->where('status', '!=', 'pending')
            ->whereNotNull('approved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, approved_at)) as avg_time')
            ->value('avg_time');
            
        return round($avgTime ?? 0, 2);
    }

    /**
     * Bulk approve multiple clearances
     */
    public function bulkApprove(array $approvalIds, $remarks = null)
    {
        $results = [];
        
        foreach ($approvalIds as $approvalId) {
            try {
                $result = $this->approve($approvalId, $remarks);
                $results[] = ['id' => $approvalId, 'status' => 'success'];
            } catch (\Exception $e) {
                $results[] = ['id' => $approvalId, 'status' => 'failed', 'error' => $e->getMessage()];
            }
        }
        
        return $results;
    }

    /**
     * Check if department officer can approve
     */
    public function canApprove($approvalId, $userId)
    {
        $approval = ClearanceApproval::with('department')->findOrFail($approvalId);
        return $approval->department->officer_user_id === $userId && $approval->status === 'pending';
    }
}