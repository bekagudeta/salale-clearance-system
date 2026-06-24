<?php

namespace App\Services;

use App\Models\ClearanceRequest;
use App\Models\ClearanceApproval;
use App\Repositories\Interfaces\ClearanceRepositoryInterface;
use App\Traits\GeneratesReference;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use App\Events\ClearanceSubmitted;

class ClearanceService
{
    use GeneratesReference, LogsActivity;

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
     * Create new clearance request
     */
    public function createClearance(array $data, $studentId)
    {
        return DB::transaction(function () use ($data, $studentId) {
            $student = \App\Models\Student::with('academicDepartment')->findOrFail($studentId);

            // Stage one: the request must first go to the student's own academic
            // department head/coordinator. Without a linked, active academic
            // department there is no one to gate the request.
            $academicDepartment = $student->academicDepartment;

            if (! $academicDepartment || ! $academicDepartment->is_active) {
                throw new \RuntimeException(
                    'Your academic department has no head/coordinator assigned yet. Please contact the registrar before submitting a clearance request.'
                );
            }

            $clearanceData = [
                'student_id' => $studentId,
                'reference_no' => $this->generateReferenceNumber(),
                'type' => $data['type'],
                'reason' => $data['reason'] ?? null,
                'status' => 'pending',
                'requested_date' => now(),
            ];

            $clearance = $this->clearanceRepository->create($clearanceData);

            // Only the academic head's approval is created now. Service
            // departments are opened later, once the head approves.
            ClearanceApproval::create([
                'clearance_request_id' => $clearance->id,
                'department_id' => $academicDepartment->id,
                'status' => 'pending',
            ]);

            // Log activity
            $this->logActivity(
                'create_clearance',
                'clearance_requests',
                $clearance->id,
                "Created clearance request with reference: {$clearance->reference_no}"
            );

            // Dispatch event
            Event::dispatch(new ClearanceSubmitted($clearance));

            return $clearance;
        });
    }

    /**
     * Open stage two: create pending approvals for every active service
     * department once the academic head has approved. Idempotent — existing
     * service approvals are left untouched. Caller is responsible for the
     * surrounding transaction and for dispatching ClearanceForwarded.
     *
     * @return bool true if any new service approvals were created
     */
    public function forwardToServiceDepartments(ClearanceRequest $clearance): bool
    {
        $existingDeptIds = $clearance->approvals()->pluck('department_id')->all();

        $serviceDepartments = \App\Models\Department::service()
            ->where('is_active', true)
            ->whereNotIn('id', $existingDeptIds)
            ->orderBy('priority_order')
            ->get();

        foreach ($serviceDepartments as $department) {
            ClearanceApproval::create([
                'clearance_request_id' => $clearance->id,
                'department_id' => $department->id,
                'status' => 'pending',
            ]);
        }

        return $serviceDepartments->isNotEmpty();
    }

    /**
     * Get all clearances for a student
     */
    public function getStudentClearances($studentId)
    {
        return $this->clearanceRepository->findByStudent($studentId);
    }

    /**
     * Get clearance details with relationships
     */
    public function getClearanceDetails($id)
    {
        return $this->clearanceRepository->findById($id);
    }

    /**
     * Get student clearance statistics
     */
    public function getStudentStats($studentId)
    {
        return $this->clearanceRepository->getStatsByStudent($studentId);
    }

    /**
     * Get all clearances with filters
     */
    public function getAllClearances(array $filters = [])
    {
        $query = ClearanceRequest::with(['student', 'approvals.department']);
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        if (isset($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }
        
        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('reference_no', 'like', "%{$filters['search']}%")
                  ->orWhereHas('student', function($sq) use ($filters) {
                      $sq->where('student_id', 'like', "%{$filters['search']}%")
                        ->orWhere('full_name', 'like', "%{$filters['search']}%");
                  });
            });
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 20);
    }

    /**
     * Update clearance status
     */
    public function updateStatus($id, $status)
    {
        return DB::transaction(function () use ($id, $status) {
            $clearance = $this->clearanceRepository->updateStatus($id, $status);
            
            $this->logActivity(
                'update_clearance_status',
                'clearance_requests',
                $clearance->id,
                "Updated clearance status to: {$status}"
            );
            
            return $clearance;
        });
    }

    /**
     * Cancel clearance request
     */
    public function cancelClearance($id, $studentId)
    {
        $clearance = ClearanceRequest::where('id', $id)
            ->where('student_id', $studentId)
            ->firstOrFail();
            
        if (!in_array($clearance->status, ['pending', 'in_progress'])) {
            throw new \Exception('Cannot cancel clearance at this stage.');
        }
        
        $clearance->update(['status' => 'cancelled']);
        
        $this->logActivity(
            'cancel_clearance',
            'clearance_requests',
            $clearance->id,
            "Cancelled clearance request: {$clearance->reference_no}"
        );
        
        return $clearance;
    }

    /**
     * Resubmit a request the academic head returned for fixing. Reuses the
     * same request: updates the reason/notes, resets the head's approval back
     * to pending, and re-notifies the head. No new request is created.
     */
    public function resubmitClearance($id, $studentId, array $data = [])
    {
        return DB::transaction(function () use ($id, $studentId, $data) {
            $clearance = ClearanceRequest::with('approvals.department')
                ->where('id', $id)
                ->where('student_id', $studentId)
                ->firstOrFail();

            if ($clearance->status !== 'returned') {
                throw new \RuntimeException('Only a returned request can be resubmitted.');
            }

            if (array_key_exists('reason', $data)) {
                $clearance->reason = $data['reason'];
            }

            $approval = $clearance->academicApproval();

            if (! $approval) {
                throw new \RuntimeException('This request has no academic department to resubmit to.');
            }

            // Send it back to the head as a fresh pending approval.
            $approval->update([
                'status' => 'pending',
                'approved_by' => null,
                'remarks' => null,
                'approved_at' => null,
            ]);

            $clearance->status = 'pending';
            $clearance->save();

            $this->logActivity(
                'resubmit_clearance',
                'clearance_requests',
                $clearance->id,
                "Resubmitted clearance request: {$clearance->reference_no}"
            );

            Event::dispatch(new ClearanceSubmitted($clearance));

            return $clearance;
        });
    }

    /**
     * Check if student can submit new clearance
     */
    public function canSubmitClearance($studentId)
    {
        $pendingCount = ClearanceRequest::where('student_id', $studentId)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();
            
        return $pendingCount === 0;
    }

    /**
     * Get clearance progress percentage
     */
    public function getProgressPercentage(ClearanceRequest $clearance)
    {
        $totalDepartments = $clearance->approvals->count();
        $approvedDepartments = $clearance->approvals->where('status', 'approved')->count();
        
        if ($totalDepartments === 0) {
            return 0;
        }
        
        return round(($approvedDepartments / $totalDepartments) * 100);
    }
}