<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Department\Concerns\ResolvesOfficerDepartment;
use App\Http\Requests\Department\ApprovalRequest;
use App\Http\Requests\Department\FlagCaseRequest;
use App\Services\ApprovalService;
use App\Services\StudentCaseService;
use App\Models\ClearanceApproval;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    use ResolvesOfficerDepartment;

    public function __construct(
        protected ApprovalService $approvalService,
        protected StudentCaseService $studentCaseService
    ) {
    }

    public function index(Request $request)
    {
        $department = $this->officerDepartment();
        $searchStudentId = trim((string) $request->query('student_id', ''));

        $pendingApprovals = $this->approvalService->getDepartmentPendingApprovals(
            $department->id,
            $searchStudentId ?: null
        );

        $studentIds = $pendingApprovals->pluck('request.student_id')->unique()->filter()->all();
        $openCasesByStudent = $this->studentCaseService->getOpenCasesGroupedByStudent(
            $department->id,
            $studentIds
        );

        return view('department.approvals.index', compact(
            'pendingApprovals',
            'openCasesByStudent',
            'searchStudentId'
        ));
    }

    public function approve(ApprovalRequest $request, $id)
    {
        ClearanceApproval::findOrFail($id);

        try {
            $this->approvalService->approve($id, $request->remarks);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Clearance approved successfully.');
    }

    public function flagCase(FlagCaseRequest $request, $id)
    {
        ClearanceApproval::findOrFail($id);

        try {
            $this->approvalService->flagWithCase($id, $request->remarks);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Student notified to clear their case before approval.');
    }

    public function reject(ApprovalRequest $request, $id)
    {
        ClearanceApproval::findOrFail($id);

        $this->approvalService->reject($id, $request->remarks);

        return redirect()->back()->with('success', 'Clearance rejected successfully.');
    }
}
