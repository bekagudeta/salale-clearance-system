<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\ClearanceApproval;
use App\Models\ClearanceRequest;
use App\Models\Department;
use App\Services\ReportService;
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

        $stats = [
            'total_requests' => ClearanceRequest::count(),
            'pending' => ClearanceRequest::where('status', 'pending')->count(),
            'in_progress' => ClearanceRequest::where('status', 'in_progress')->count(),
            'approved' => ClearanceRequest::where('status', 'approved')->count(),
            'awaiting_registrar' => $awaitingRegistrarCount,
            'completed' => ClearanceRequest::where('status', 'completed')->count(),
            'rejected' => ClearanceRequest::where('status', 'rejected')->count(),
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