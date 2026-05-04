<?php

namespace App\Repositories;

use App\Models\ClearanceRequest;
use App\Models\ClearanceApproval;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportRepositoryInterface
{
    public function getClearedStudentsByMonth($month, $year)
    {
        return ClearanceRequest::where('status', 'completed')
            ->whereMonth('completed_at', $month)
            ->whereYear('completed_at', $year)
            ->with('student')
            ->get();
    }

    public function getRejectedRequests($fromDate, $toDate)
    {
        return ClearanceRequest::where('status', 'rejected')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->with(['student', 'approvals' => function($q) {
                $q->where('status', 'rejected');
            }])
            ->get();
    }

    public function getDepartmentDelays()
    {
        return ClearanceApproval::where('status', 'pending')
            ->where('created_at', '<', now()->subDays(3))
            ->with(['department', 'request.student'])
            ->get()
            ->groupBy('department.name');
    }

    public function getGraduationStatistics($year)
    {
        return [
            'total_graduations' => ClearanceRequest::where('type', 'graduation')
                ->whereYear('created_at', $year)
                ->count(),
            'completed_graduations' => ClearanceRequest::where('type', 'graduation')
                ->where('status', 'completed')
                ->whereYear('completed_at', $year)
                ->count(),
            'rejected_graduations' => ClearanceRequest::where('type', 'graduation')
                ->where('status', 'rejected')
                ->whereYear('created_at', $year)
                ->count(),
        ];
    }

    public function getFacultyBasedReports($faculty)
    {
        return ClearanceRequest::whereHas('student', function($q) use ($faculty) {
            $q->where('faculty', $faculty);
        })
        ->select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->get();
    }
}