<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\ClearanceApproval;
use App\Models\ClearanceRequest;
use App\Models\Department;
use App\Services\ReportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $registrarDepartment = Department::where('slug', 'registrar-office')->first();
        $awaitingRegistrarCount = 0;

        if ($registrarDepartment) {
            $awaitingRegistrarCount = ClearanceApproval::where('department_id', $registrarDepartment->id)
                ->where('status', 'pending')
                ->count();
        }

        // Collapse the six status counters into one aggregate query, cached briefly.
        $requestStats = Cache::remember('registrar_dashboard_request_stats', 60, function () {
            return ClearanceRequest::selectRaw("
                COUNT(*) as total_requests,
                SUM(status = 'pending') as pending,
                SUM(status = 'in_progress') as in_progress,
                SUM(status = 'approved') as approved,
                SUM(status = 'completed') as completed,
                SUM(status = 'rejected') as rejected
            ")->first();
        });

        $stats = [
            'total_requests' => (int) ($requestStats->total_requests ?? 0),
            'pending' => (int) ($requestStats->pending ?? 0),
            'in_progress' => (int) ($requestStats->in_progress ?? 0),
            'approved' => (int) ($requestStats->approved ?? 0),
            'awaiting_registrar' => $awaitingRegistrarCount,
            'completed' => (int) ($requestStats->completed ?? 0),
            'rejected' => (int) ($requestStats->rejected ?? 0),
        ];
        
        $monthlyStats = ClearanceRequest::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('MONTHNAME(created_at) as month_name'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month', 'month_name')
        ->orderBy('month')
        ->get()
        ->keyBy('month')
        ->map(function ($item) {
            return [
                'month' => (int) $item->month,
                'month_name' => $item->month_name,
                'total' => (int) $item->total,
                'completed' => (int) $item->completed,
            ];
        });

        $monthlyStats = collect(range(1, 12))->map(function ($month) use ($monthlyStats) {
            return $monthlyStats->get($month, [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'total' => 0,
                'completed' => 0,
            ]);
        });
        
        $recentRequests = ClearanceRequest::with(['student'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $awaitingRegistrar = collect();
        if ($registrarDepartment) {
            $awaitingRegistrar = ClearanceRequest::whereHas('approvals', function($query) use ($registrarDepartment) {
                    $query->where('department_id', $registrarDepartment->id)
                          ->where('status', 'pending');
                })
                ->with(['student', 'approvals.department'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }
        
        $graduationStats = $this->reportService->getGraduationStatistics(date('Y'));
        
        return view('registrar.dashboard', compact('stats', 'monthlyStats', 'recentRequests', 'awaitingRegistrar', 'graduationStats'));
    }
}