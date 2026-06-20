<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClearanceRequest;
use App\Models\Department;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // System Statistics (cached briefly — these change slowly and are read on every load).
        $stats = Cache::remember('admin_dashboard_stats', 60, function () {
            // Collapse the three clearance counters into one aggregate query.
            $clearance = ClearanceRequest::selectRaw("
                COUNT(*) as total,
                SUM(status = 'pending') as pending,
                SUM(status = 'completed') as completed
            ")->first();

            return [
                'total_users' => User::count(),
                'total_students' => User::role('student')->count(),
                'total_officers' => User::role('department_officer')->count(),
                'total_clearances' => (int) ($clearance->total ?? 0),
                'pending_clearances' => (int) ($clearance->pending ?? 0),
                'completed_clearances' => (int) ($clearance->completed ?? 0),
                'active_departments' => Department::where('is_active', true)->count(),
            ];
        });
        
        // Monthly Clearance Trends
        $monthlyTrends = ClearanceRequest::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        
        // Recent Activities
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Department Performance
        $departmentPerformance = Department::withCount(['approvals as total_approvals' => function($q) {
                $q->where('status', '!=', 'pending');
            }])
            ->withCount(['approvals as approved_count' => function($q) {
                $q->where('status', 'approved');
            }])
            ->withCount(['approvals as rejected_count' => function($q) {
                $q->where('status', 'rejected');
            }])
            ->get();
        
        // System Health — the DB-size and disk lookups are expensive (information_schema
        // scan + full disk stat), so cache the slow parts for 5 minutes. Job counts stay
        // live since they change second-to-second.
        $health = Cache::remember('admin_dashboard_health', 300, function () {
            return [
                'database_size' => $this->getDatabaseSize(),
                'storage_used' => $this->getStorageUsed(),
                'last_backup' => $this->getLastBackupDate(),
            ];
        });

        $systemHealth = $health + [
            'pending_jobs' => DB::table('jobs')->count(),
            'failed_jobs' => DB::table('failed_jobs')->count(),
        ];
        
        return view('admin.dashboard', compact(
            'stats', 
            'monthlyTrends', 
            'recentActivities', 
            'departmentPerformance',
            'systemHealth'
        ));
    }
    
    private function getDatabaseSize()
    {
        $databaseName = config('database.connections.mysql.database');
        $result = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size 
                              FROM information_schema.tables 
                              WHERE table_schema = '$databaseName'");
        
        return $result[0]->size ?? 0;
    }
    
    private function getStorageUsed()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        
        return round($used / 1024 / 1024 / 1024, 2); // GB
    }
    
    private function getLastBackupDate()
    {
        $backupPath = storage_path('app/backups');
        if (!is_dir($backupPath)) {
            return null;
        }
        
        $files = glob($backupPath . '/*.sql');
        if (empty($files)) {
            return null;
        }
        
        $lastBackup = max($files);
        return date('Y-m-d H:i:s', filemtime($lastBackup));
    }
}