<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\ClearanceRequest;
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
        $stats = [
            'total_requests' => ClearanceRequest::count(),
            'pending' => ClearanceRequest::where('status', 'pending')->count(),
            'in_progress' => ClearanceRequest::where('status', 'in_progress')->count(),
            'approved' => ClearanceRequest::where('status', 'approved')->count(),
            'awaiting_final' => ClearanceRequest::where('status', 'approved')->count(),
            'completed' => ClearanceRequest::where('status', 'completed')->count(),
            'rejected' => ClearanceRequest::where('status', 'rejected')->count(),
        ];
        
        $monthlyStats = ClearanceRequest::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->get();
        
        $recentRequests = ClearanceRequest::with(['student'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        $graduationStats = $this->reportService->getGraduationStatistics(date('Y'));
        
        return view('registrar.dashboard', compact('stats', 'monthlyStats', 'recentRequests', 'graduationStats'));
    }
}