<?php

namespace App\Services;

use App\Models\ClearanceApproval;
use App\Repositories\Interfaces\ClearanceRepositoryInterface;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use App\Events\ClearanceApproved;
use App\Events\ClearanceForwarded;
use App\Events\ClearanceRejected;

class ApprovalService
{
    use LogsActivity;

    protected $clearanceRepository;
    protected $notificationService;
    protected $clearanceService;
    protected $studentCaseService;

    public function __construct(
        ClearanceRepositoryInterface $clearanceRepository,
        NotificationService $notificationService,
        ClearanceService $clearanceService,
        StudentCaseService $studentCaseService
    ) {
        $this->clearanceRepository = $clearanceRepository;
        $this->notificationService = $notificationService;
        $this->clearanceService = $clearanceService;
        $this->studentCaseService = $studentCaseService;
    }

    /**
     * Approve a clearance request
     */
    public function approve($approvalId, $remarks = null)
    {
        return DB::transaction(function () use ($approvalId, $remarks) {
            $approval = ClearanceApproval::with(['request.student.user', 'department'])->findOrFail($approvalId);

            $isAuthorized = ($approval->department->officer_user_id === auth()->id())
                || auth()->user()->departments()->wherePivot('can_approve', true)
                    ->where('departments.id', $approval->department_id)
                    ->exists()
                || (auth()->user()->hasRole('registrar') && $approval->department->slug === 'registrar-office');

            if (! $isAuthorized) {
                throw new \Exception('You are not authorized to process this approval.');
            }
            
            if (! in_array($approval->status, ['pending', 'on_hold'], true)) {
                throw new \Exception('This approval has already been processed.');
            }

            if ($this->studentCaseService->hasOpenCases(
                $approval->department_id,
                $approval->request->student_id
            )) {
                throw new \Exception(
                    'Cannot approve: this student has open case(s) on record. Clear the cases first or notify the student.'
                );
            }
            
            $approval->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'remarks' => $remarks,
                'approved_at' => now(),
            ]);

            // Stage one → stage two: the academic head's approval opens the
            // request up to the service departments. Done inside this same
            // transaction so the request never momentarily looks "fully
            // approved" with only the head's approval present.
            $forwarded = false;
            if ($approval->department->isAcademic()
                && ! $approval->request->hasServiceApprovals()) {
                $forwarded = $this->clearanceService->forwardToServiceDepartments($approval->request);
                $approval->request->load('approvals.department');
            }

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

            if ($forwarded) {
                Event::dispatch(new ClearanceForwarded($approval->request));
            }

            return $approval;
        });
    }

    /**
     * Put a clearance on hold because the student has open case(s).
     */
    public function flagWithCase($approvalId, $remarks)
    {
        return DB::transaction(function () use ($approvalId, $remarks) {
            $approval = ClearanceApproval::with(['request.student.user', 'department'])->findOrFail($approvalId);

            $isAuthorized = ($approval->department->officer_user_id === auth()->id())
                || auth()->user()->departments()->wherePivot('can_approve', true)
                    ->where('departments.id', $approval->department_id)
                    ->exists()
                || (auth()->user()->hasRole('registrar') && $approval->department->slug === 'registrar-office');

            if (! $isAuthorized) {
                throw new \Exception('You are not authorized to process this approval.');
            }

            if (! in_array($approval->status, ['pending', 'on_hold'], true)) {
                throw new \Exception('This approval has already been processed.');
            }

            if (! $this->studentCaseService->hasOpenCases(
                $approval->department_id,
                $approval->request->student_id
            )) {
                throw new \Exception('This student has no open cases. You can approve the request directly.');
            }

            $approval->update([
                'status' => 'on_hold',
                'remarks' => $remarks,
            ]);

            $studentUser = $approval->request->student->user;
            if ($studentUser) {
                $this->notificationService->notifyCaseHold(
                    $studentUser,
                    $approval->request,
                    $approval->department,
                    $remarks
                );
            }

            $this->logActivity(
                'flag_clearance_case',
                'clearance_approvals',
                $approval->id,
                "Flagged clearance on hold for {$approval->department->name}: {$remarks}"
            );

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

            $isAuthorized = ($approval->department->officer_user_id === auth()->id())
                || auth()->user()->departments()->wherePivot('can_approve', true)
                    ->where('departments.id', $approval->department_id)
                    ->exists()
                || (auth()->user()->hasRole('registrar') && $approval->department->slug === 'registrar-office');

            if (! $isAuthorized) {
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

            if ($approval->department->isAcademic()) {
                // The academic head returns the request to the student to fix
                // and resubmit, rather than killing it outright.
                $approval->request->update(['status' => 'returned']);
            } else {
                // Update main request status
                $approval->request->updateStatusFromApprovals();
            }

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
    public function getDepartmentPendingApprovals($departmentId, ?string $studentIdSearch = null)
    {
        $query = ClearanceApproval::where('department_id', $departmentId)
            ->whereIn('status', ['pending', 'on_hold'])
            ->with(['request.student']);

        if ($studentIdSearch) {
            $query->whereHas('request.student', function ($q) use ($studentIdSearch) {
                $q->where('student_id', 'like', '%' . $studentIdSearch . '%');
            });
        }

        return $query->orderBy('created_at', 'asc')->get();
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
        // Single aggregate query instead of 5 separate COUNT queries.
        $row = ClearanceApproval::where('department_id', $departmentId)
            ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'pending') + SUM(status = 'on_hold') as pending,
                SUM(status = 'approved') as approved,
                SUM(status = 'rejected') as rejected,
                SUM(status = 'approved' AND DATE(approved_at) = CURDATE()) as approved_today
            ")
            ->first();

        return [
            'total' => (int) ($row->total ?? 0),
            'pending' => (int) ($row->pending ?? 0),
            'approved' => (int) ($row->approved ?? 0),
            'rejected' => (int) ($row->rejected ?? 0),
            'approved_today' => (int) ($row->approved_today ?? 0),
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
        $assignedViaPrimary = $approval->department->officer_user_id === $userId;
        $assignedViaPivot = \App\Models\User::where('id', $userId)
            ->whereHas('departments', function($q) use ($approval) {
                $q->wherePivot('can_approve', true)
                    ->where('departments.id', $approval->department_id);
            })->exists();

        $assignedViaRegistrar = \App\Models\User::where('id', $userId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'registrar');
            })->exists() && $approval->department->slug === 'registrar-office';

        return ($assignedViaPrimary || $assignedViaPivot || $assignedViaRegistrar)
            && in_array($approval->status, ['pending', 'on_hold'], true);
    }
}