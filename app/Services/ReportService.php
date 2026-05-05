<?php

namespace App\Services;

use App\Models\ClearanceRequest;
use App\Models\ClearanceApproval;
use App\Models\Student;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReportService
{
    protected $reportRepository;

    public function __construct(ReportRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /**
     * Get cleared students by month
     */
    public function getClearedStudentsByMonth($month, $year)
    {
        return $this->reportRepository->getClearedStudentsByMonth($month, $year);
    }

    /**
     * Get rejected requests within date range
     */
    public function getRejectedRequests($fromDate, $toDate)
    {
        return $this->reportRepository->getRejectedRequests($fromDate, $toDate);
    }

    /**
     * Get department delays (pending > 3 days)
     */
    public function getDepartmentDelays()
    {
        return $this->reportRepository->getDepartmentDelays();
    }

    /**
     * Get graduation statistics for a year
     */
    public function getGraduationStatistics($year)
    {
        return $this->reportRepository->getGraduationStatistics($year);
    }

    /**
     * Get faculty based reports
     */
    public function getFacultyBasedReports($faculty)
    {
        return $this->reportRepository->getFacultyBasedReports($faculty);
    }

    /**
     * Get comprehensive dashboard statistics
     */
    public function getDashboardStats()
    {
        return [
            'total_clearances' => ClearanceRequest::count(),
            'pending_clearances' => ClearanceRequest::where('status', 'pending')->count(),
            'in_progress' => ClearanceRequest::where('status', 'in_progress')->count(),
            'completed_clearances' => ClearanceRequest::where('status', 'completed')->count(),
            'rejected_clearances' => ClearanceRequest::where('status', 'rejected')->count(),
            'total_students' => Student::count(),
            'average_completion_days' => $this->getAverageCompletionDays(),
            'monthly_trend' => $this->getMonthlyTrend(),
        ];
    }

    /**
     * Get average completion days
     */
    private function getAverageCompletionDays()
    {
        $avgDays = ClearanceRequest::where('status', 'completed')
            ->whereNotNull('completed_at')
            ->selectRaw('AVG(DATEDIFF(completed_at, created_at)) as avg_days')
            ->value('avg_days');
            
        return round($avgDays ?? 0, 2);
    }

    /**
     * Get monthly trend for current year
     */
    private function getMonthlyTrend()
    {
        return ClearanceRequest::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                $item->month_name = date('F', mktime(0, 0, 0, $item->month, 1));
                return $item;
            });
    }

    /**
     * Get department performance report
     */
    public function getDepartmentPerformance()
    {
        return DB::table('departments')
            ->leftJoin('clearance_approvals', 'departments.id', '=', 'clearance_approvals.department_id')
            ->select(
                'departments.id',
                'departments.name',
                DB::raw('COUNT(clearance_approvals.id) as total_approvals'),
                DB::raw('SUM(CASE WHEN clearance_approvals.status = "approved" THEN 1 ELSE 0 END) as approved_count'),
                DB::raw('SUM(CASE WHEN clearance_approvals.status = "rejected" THEN 1 ELSE 0 END) as rejected_count'),
                DB::raw('SUM(CASE WHEN clearance_approvals.status = "pending" THEN 1 ELSE 0 END) as pending_count'),
                DB::raw('AVG(CASE WHEN clearance_approvals.status != "pending" THEN TIMESTAMPDIFF(HOUR, clearance_approvals.created_at, clearance_approvals.approved_at) ELSE NULL END) as avg_processing_hours')
            )
            ->groupBy('departments.id', 'departments.name')
            ->get();
    }

    /**
     * Get clearance type distribution
     */
    public function getTypeDistribution()
    {
        return ClearanceRequest::select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->get()
            ->map(function($item) {
                $item->type_label = ucfirst(str_replace('_', ' ', $item->type));
                return $item;
            });
    }

    /**
     * Export report to array for CSV/PDF
     */
    public function exportReport($type, array $params = [])
    {
        switch ($type) {
            case 'cleared_students':
                $data = $this->getClearedStudentsByMonth($params['month'], $params['year']);
                break;
            case 'rejected_requests':
                $data = $this->getRejectedRequests($params['from_date'], $params['to_date']);
                break;
            case 'department_delays':
                $data = $this->getDepartmentDelays();
                break;
            case 'graduation_stats':
                $data = $this->getGraduationStatistics($params['year']);
                break;
            default:
                $data = [];
        }
        
        return [
            'data' => $data,
            'generated_at' => now(),
            'type' => $type,
            'params' => $params,
        ];
    }
}