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
            $clearanceData = [
                'student_id' => $studentId,
                'reference_no' => $this->generateReferenceNumber(),
                'type' => $data['type'],
                'reason' => $data['reason'] ?? null,
                'status' => 'pending',
                'requested_date' => now(),
            ];
            
            $clearance = $this->clearanceRepository->create($clearanceData);

            $departments = \App\Models\Department::where('is_active', true)
                ->orderBy('priority_order')
                ->get();

            foreach ($departments as $department) {
                ClearanceApproval::create([
                    'clearance_request_id' => $clearance->id,
                    'department_id' => $department->id,
                    'status' => 'pending',
                ]);
            }
            
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