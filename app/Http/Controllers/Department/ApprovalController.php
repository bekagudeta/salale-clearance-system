<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\ApprovalRequest;
use App\Services\ApprovalService;
use App\Models\Department;
use App\Models\ClearanceApproval;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    protected $approvalService;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    public function index()
    {
        $department = Auth::user()->assignedDepartments->first() ?? Auth::user()->departments()->wherePivot('can_approve', true)->first();
        $pendingApprovals = $this->approvalService->getDepartmentPendingApprovals($department->id);
        
        return view('department.approvals.index', compact('pendingApprovals'));
    }

    public function approve(ApprovalRequest $request, $id)
    {
        $approval = ClearanceApproval::findOrFail($id);
        $this->authorize('approve', $approval);

        $this->approvalService->approve($id, $request->remarks);

        return redirect()->back()->with('success', 'Clearance approved successfully.');
    }

    public function reject(ApprovalRequest $request, $id)
    {
        $approval = ClearanceApproval::findOrFail($id);
        $this->authorize('reject', $approval);

        $this->approvalService->reject($id, $request->remarks);

        return redirect()->back()->with('success', 'Clearance rejected successfully.');
    }
}