<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\ApprovalRequest;
use App\Services\ApprovalService;
use App\Models\Department;
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
        $department = Auth::user()->assignedDepartments->first();
        $pendingApprovals = $this->approvalService->getDepartmentPendingApprovals($department->id);
        
        return view('department.approvals.index', compact('pendingApprovals'));
    }

    public function approve(ApprovalRequest $request, $id)
    {
        $this->approvalService->approve($id, $request->remarks);
        
        return redirect()->back()->with('success', 'Clearance approved successfully.');
    }

    public function reject(ApprovalRequest $request, $id)
    {
        $this->approvalService->reject($id, $request->remarks);
        
        return redirect()->back()->with('success', 'Clearance rejected successfully.');
    }
}