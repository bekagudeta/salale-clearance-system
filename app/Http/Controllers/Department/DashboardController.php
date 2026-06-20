<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ClearanceApproval;
use App\Services\ApprovalService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    protected $approvalService;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    public function index()
    {
        $department = Auth::user()->assignedDepartments->first() ?? Auth::user()->departments()->wherePivot('can_approve', true)->first();
        
        if (!$department) {
            return redirect()->route('home')->with('error', 'No department assigned.');
        }
        
        $user = Auth::user();

        // Direct COUNT query instead of loading every pending row into memory.
        $pendingCount = ClearanceApproval::where('department_id', $department->id)
            ->where('status', 'pending')
            ->count();

        $recentApprovals = $user
            ->approvals()
            ->with(['request.student'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Collapse the three user-scoped counters into a single aggregate query,
        // cached briefly to avoid recomputing on every dashboard refresh.
        $userStats = Cache::remember("dept_dashboard_stats_user_{$user->id}", 60, function () use ($user) {
            return $user->approvals()
                ->selectRaw("
                    SUM(approved_at IS NOT NULL AND DATE(approved_at) = CURDATE()) as approved_today,
                    SUM(status = 'rejected' AND DATE(created_at) = CURDATE()) as rejected_today,
                    COUNT(*) as total_processed
                ")
                ->first();
        });

        $stats = [
            'pending' => $pendingCount,
            'approved_today' => (int) ($userStats->approved_today ?? 0),
            'rejected_today' => (int) ($userStats->rejected_today ?? 0),
            'total_processed' => (int) ($userStats->total_processed ?? 0),
        ];
        
        return view('department.dashboard', compact('department', 'pendingCount', 'recentApprovals', 'stats'));
    }

    public function statistics()
    {
        $department = Auth::user()->assignedDepartments->first() ?? Auth::user()->departments()->wherePivot('can_approve', true)->first();

        if (!$department) {
            return redirect()->route('home')->with('error', 'No department assigned.');
        }

        $stats = $this->approvalService->getDepartmentStats($department->id);

        return view('department.statistics', compact('department', 'stats'));
    }
}