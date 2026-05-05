<?php

namespace App\Services;

use App\Models\User;
use App\Models\ClearanceRequest;
use App\Models\Department;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get statistics for student dashboard
     */
    public function getStudentStats($studentId)
    {
        return [
            'total_clearances' => ClearanceRequest::where('student_id', $studentId)->count(),
            'pending' => ClearanceRequest::where('student_id', $studentId)
                ->whereIn('status', ['pending', 'in_progress'])
                ->count(),
            'approved' => ClearanceRequest::where('student_id', $studentId)
                ->where('status', 'approved')
                ->count(),
            'rejected' => ClearanceRequest::where('student_id', $studentId)
                ->where('status', 'rejected')
                ->count(),
            'completed' => ClearanceRequest::where('student_id', $studentId)
                ->where('status', 'completed')
                ->count(),
            'recent_activities' => $this->getStudentRecentActivities($studentId),
        ];
    }

    /**
     * Get recent activities for student
     */
    private function getStudentRecentActivities($studentId)
    {
        return ActivityLog::whereHas('user.student', function($q) use ($studentId) {
                $q->where('id', $studentId);
            })
            ->orWhere('record_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Get statistics for department dashboard
     */
    public function getDepartmentStats($departmentId)
    {
        $pendingCount = \App\Models\ClearanceApproval::where('department_id', $departmentId)
            ->where('status', 'pending')
            ->count();
            
        return [
            'pending_approvals' => $pendingCount,
            'approved_today' => \App\Models\ClearanceApproval::where('department_id', $departmentId)
                ->whereDate('approved_at', today())
                ->where('status', 'approved')
                ->count(),
            'rejected_today' => \App\Models\ClearanceApproval::where('department_id', $departmentId)
                ->whereDate('created_at', today())
                ->where('status', 'rejected')
                ->count(),
            'total_processed' => \App\Models\ClearanceApproval::where('department_id', $departmentId)
                ->where('status', '!=', 'pending')
                ->count(),
            'average_processing_time' => $this->getDepartmentAvgProcessingTime($departmentId),
        ];
    }

    /**
     * Get department average processing time
     */
    private function getDepartmentAvgProcessingTime($departmentId)
    {
        $avg = \App\Models\ClearanceApproval::where('department_id', $departmentId)
            ->where('status', '!=', 'pending')
            ->whereNotNull('approved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, approved_at)) as avg_hours')
            ->value('avg_hours');
            
        return round($avg ?? 0, 2);
    }

    /**
     * Get statistics for registrar dashboard
     */
    public function getRegistrarStats()
    {
        return [
            'total_requests' => ClearanceRequest::count(),
            'pending' => ClearanceRequest::whereIn('status', ['pending', 'in_progress'])->count(),
            'awaiting_final' => ClearanceRequest::where('status', 'approved')->count(),
            'completed_this_month' => ClearanceRequest::where('status', 'completed')
                ->whereMonth('completed_at', now()->month)
                ->count(),
            'rejected_this_month' => ClearanceRequest::where('status', 'rejected')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'completion_rate' => $this->getCompletionRate(),
        ];
    }

    /**
     * Get overall completion rate
     */
    private function getCompletionRate()
    {
        $total = ClearanceRequest::count();
        $completed = ClearanceRequest::where('status', 'completed')->count();
        
        if ($total === 0) return 0;
        
        return round(($completed / $total) * 100, 2);
    }

    /**
     * Get statistics for admin dashboard
     */
    public function getAdminStats()
    {
        return [
            'total_users' => User::count(),
            'total_students' => User::role('student')->count(),
            'total_officers' => User::role('department_officer')->count(),
            'total_clearances' => ClearanceRequest::count(),
            'active_departments' => Department::where('is_active', true)->count(),
            'storage_usage' => $this->getStorageUsage(),
            'database_size' => $this->getDatabaseSize(),
        ];
    }

    /**
     * Get storage usage
     */
    private function getStorageUsage()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        
        return [
            'total_gb' => round($total / 1024 / 1024 / 1024, 2),
            'used_gb' => round($used / 1024 / 1024 / 1024, 2),
            'free_gb' => round($free / 1024 / 1024 / 1024, 2),
            'percentage_used' => round(($used / $total) * 100, 2),
        ];
    }

    /**
     * Get database size
     */
    private function getDatabaseSize()
    {
        $databaseName = config('database.connections.mysql.database');
        $result = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb 
                              FROM information_schema.tables 
                              WHERE table_schema = '$databaseName'");
                              
        return $result[0]->size_mb ?? 0;
    }

    /**
     * Get chart data for dashboard
     */
    public function getChartData($type, $period = 'monthly')
    {
        switch ($type) {
            case 'clearance_trend':
                return $this->getClearanceTrend($period);
            case 'department_performance':
                return $this->getDepartmentPerformanceChart();
            case 'status_distribution':
                return $this->getStatusDistribution();
            default:
                return [];
        }
    }

    /**
     * Get clearance trend data for charts
     */
    private function getClearanceTrend($period)
    {
        $query = ClearanceRequest::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            );
            
        if ($period === 'weekly') {
            $query->whereDate('created_at', '>=', now()->subDays(7));
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', now()->month);
        } else {
            $query->whereYear('created_at', now()->year);
        }
        
        return $query->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get department performance for charts
     */
    private function getDepartmentPerformanceChart()
    {
        return Department::withCount(['approvals as total' => function($q) {
                $q->where('status', '!=', 'pending');
            }])
            ->withCount(['approvals as approved' => function($q) {
                $q->where('status', 'approved');
            }])
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function($dept) {
                return [
                    'name' => $dept->name,
                    'total' => $dept->total,
                    'approved' => $dept->approved,
                ];
            });
    }

    /**
     * Get status distribution for charts
     */
    private function getStatusDistribution()
    {
        return ClearanceRequest::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(function($item) {
                $item->label = ucfirst(str_replace('_', ' ', $item->status));
                return $item;
            });
    }

    /**
     * Get recent system activities
     */
    public function getRecentActivities($limit = 10)
    {
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}