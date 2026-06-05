<?php

namespace App\Repositories;

use App\Models\ClearanceRequest;
use App\Models\ClearanceApproval;
use App\Repositories\Interfaces\ClearanceRepositoryInterface;

class ClearanceRepository implements ClearanceRepositoryInterface
{
    public function getAll()
    {
        return ClearanceRequest::with(['student', 'approvals.department'])->get();
    }

    public function findById($id)
    {
        return ClearanceRequest::with(['student.user', 'approvals.department', 'approvals.officer'])->findOrFail($id);
    }

    public function findByStudent($studentId)
    {
        return ClearanceRequest::where('student_id', $studentId)
            ->with('approvals.department')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create(array $data)
    {
        return ClearanceRequest::create($data);
    }

    public function updateStatus($id, $status)
    {
        $clearance = ClearanceRequest::findOrFail($id);
        $clearance->update(['status' => $status]);
        
        if ($status === 'completed') {
            $clearance->update(['completed_at' => now()]);
        }
        
        return $clearance;
    }

    public function getPendingByDepartment($departmentId)
    {
        return ClearanceApproval::where('department_id', $departmentId)
            ->where('status', 'pending')
            ->with(['request.student'])
            ->get();
    }

    public function getStatsByStudent($studentId)
    {
        return [
            'total' => ClearanceRequest::where('student_id', $studentId)->count(),
            'pending' => ClearanceRequest::where('student_id', $studentId)->where('status', 'pending')->count(),
            'approved' => ClearanceRequest::where('student_id', $studentId)->where('status', 'approved')->count(),
            'rejected' => ClearanceRequest::where('student_id', $studentId)->where('status', 'rejected')->count(),
            'completed' => ClearanceRequest::where('student_id', $studentId)->where('status', 'completed')->count(),
        ];
    }
}
