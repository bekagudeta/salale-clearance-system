<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Services\ApprovalService;
use Illuminate\Support\Facades\Auth;

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
        
        $pendingCount = $this->approvalService->getDepartmentPendingApprovals($department->id)->count();
        
        $recentApprovals = Auth::user()
            ->approvals()
            ->with(['request.student'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        $stats = [
            'pending' => $pendingCount,
            'approved_today' => Auth::user()
                ->approvals()
                ->whereDate('approved_at', today())
                ->count(),
            'rejected_today' => Auth::user()
                ->approvals()
                ->whereDate('created_at', today())
                ->where('status', 'rejected')
                ->count(),
            'total_processed' => Auth::user()->approvals()->count(),
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