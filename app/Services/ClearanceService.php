<?php

namespace App\Services;

use App\Repositories\Interfaces\ClearanceRepositoryInterface;
use App\Traits\GeneratesReference;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;

class ClearanceService
{
    use GeneratesReference, LogsActivity;

    protected $clearanceRepository;

    public function __construct(ClearanceRepositoryInterface $clearanceRepository)
    {
        $this->clearanceRepository = $clearanceRepository;
    }

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
            
            $this->logActivity(
                'create_clearance',
                'clearance_requests',
                $clearance->id,
                "Created clearance request with reference: {$clearance->reference_no}"
            );
            
            return $clearance;
        });
    }

    public function getStudentClearances($studentId)
    {
        return $this->clearanceRepository->findByStudent($studentId);
    }

    public function getClearanceDetails($id)
    {
        return $this->clearanceRepository->findById($id);
    }

    public function getStudentStats($studentId)
    {
        return $this->clearanceRepository->getStatsByStudent($studentId);
    }
}