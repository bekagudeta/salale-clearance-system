<?php

namespace App\Services;

use App\Models\ClearanceRequest;
use App\Models\ClearanceApproval;
use App\Models\Student;
use App\Models\Department;
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
     * Export clearances for CSV
     */
    public function exportClearances()
    {
        return ClearanceRequest::with('student')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($record) {
                return [
                    'Reference No' => $record->reference_no,
                    'Student Name' => optional($record->student)->full_name,
                    'Student ID' => optional($record->student)->student_id,
                    'Type' => ucfirst(str_replace('_', ' ', $record->type)),
                    'Reason' => $record->reason,
                    'Status' => ucfirst($record->status),
                    'Submitted At' => optional($record->created_at)->toDateTimeString(),
                    'Completed At' => optional($record->completed_at)->toDateTimeString(),
                ];
            });
    }

    /**
     * Export students for CSV
     */
    public function exportStudents()
    {
        return Student::with('user')
            ->orderBy('full_name')
            ->get()
            ->map(function ($record) {
                return [
                    'Student ID' => $record->student_id,
                    'Full Name' => $record->full_name,
                    'Faculty' => $record->faculty,
                    'Department' => $record->department,
                    'Year' => $record->year,
                    'Semester' => $record->semester,
                    'Phone' => $record->phone,
                    'Gender' => ucfirst($record->gender),
                    'Email' => optional($record->user)->email,
                ];
            });
    }

    /**
     * Export departments for CSV
     */
    public function exportDepartments()
    {
        return Department::with('officer')
            ->orderBy('name')
            ->get()
            ->map(function ($record) {
                return [
                    'Department Name' => $record->name,
                    'Slug' => $record->slug,
                    'Officer Name' => optional($record->officer)->name,
                    'Officer Email' => optional($record->officer)->email,
                    'Priority Order' => $record->priority_order,
                    'Active' => $record->is_active ? 'Yes' : 'No',
                ];
            });
    }

    /**
     * Format report data for PDF export
     */
    public function formatReportRows($type, $data)
    {
        switch ($type) {
            case 'cleared_students':
                return $data->map(function ($record) {
                    return [
                        'Reference No' => $record->reference_no,
                        'Student Name' => optional($record->student)->full_name,
                        'Student ID' => optional($record->student)->student_id,
                        'Type' => ucfirst(str_replace('_', ' ', $record->type)),
                        'Reason' => $record->reason,
                        'Status' => ucfirst($record->status),
                        'Submitted At' => optional($record->created_at)->toDateTimeString(),
                        'Completed At' => optional($record->completed_at)->toDateTimeString(),
                    ];
                });

            case 'rejected_requests':
                return $data->map(function ($record) {
                    return [
                        'Reference No' => $record->reference_no,
                        'Student Name' => optional($record->student)->full_name,
                        'Student ID' => optional($record->student)->student_id,
                        'Type' => ucfirst(str_replace('_', ' ', $record->type)),
                        'Status' => ucfirst($record->status),
                        'Rejected At' => optional($record->updated_at)->toDateTimeString(),
                        'Reason' => $record->reason,
                    ];
                });

            case 'department_delays':
                return $data->flatMap(function ($group, $department) {
                    return collect($group)->map(function ($record) use ($department) {
                        return [
                            'Department' => $department,
                            'Request Reference' => optional($record->request)->reference_no,
                            'Student Name' => optional(optional($record->request)->student)->full_name,
                            'Pending Since' => optional($record->created_at)->toDateTimeString(),
                        ];
                    });
                });

            case 'graduation_stats':
                return collect([[
                    'Total Graduations' => $data['total_graduations'] ?? 0,
                    'Completed Graduations' => $data['completed_graduations'] ?? 0,
                    'Rejected Graduations' => $data['rejected_graduations'] ?? 0,
                ]]);

            default:
                return collect($data)->map(function ($record) {
                    return is_object($record) ? $record->toArray() : (array) $record;
                });
        }
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