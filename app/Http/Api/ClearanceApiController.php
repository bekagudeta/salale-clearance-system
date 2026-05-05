<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClearanceRequest;
use App\Models\ClearanceApproval;
use App\Services\ClearanceService;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClearanceApiController extends Controller
{
    protected $clearanceService;
    protected $approvalService;

    public function __construct(ClearanceService $clearanceService, ApprovalService $approvalService)
    {
        $this->clearanceService = $clearanceService;
        $this->approvalService = $approvalService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'type', 'from_date', 'to_date', 'search', 'per_page']);
        $clearances = $this->clearanceService->getAllClearances($filters);
        
        return response()->json([
            'success' => true,
            'data' => $clearances,
            'message' => 'Clearances retrieved successfully'
        ]);
    }

    public function show($id)
    {
        $clearance = $this->clearanceService->getClearanceDetails($id);
        
        return response()->json([
            'success' => true,
            'data' => $clearance,
            'message' => 'Clearance retrieved successfully'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:graduation,withdrawal,transfer,dismissal,temporary_leave,semester_completion',
            'reason' => 'nullable|string|max:500',
        ]);

        $student = Auth::user()->student;
        $clearance = $this->clearanceService->createClearance($request->all(), $student->id);
        
        return response()->json([
            'success' => true,
            'data' => $clearance,
            'message' => 'Clearance request created successfully'
        ], 201);
    }

    public function getByStudent($studentId)
    {
        $clearances = $this->clearanceService->getStudentClearances($studentId);
        
        return response()->json([
            'success' => true,
            'data' => $clearances,
            'message' => 'Student clearances retrieved successfully'
        ]);
    }

    public function getByStatus($status)
    {
        $clearances = ClearanceRequest::where('status', $status)->with('student')->get();
        
        return response()->json([
            'success' => true,
            'data' => $clearances,
            'message' => 'Clearances by status retrieved successfully'
        ]);
    }

    public function approve($id, Request $request)
    {
        $request->validate([
            'remarks' => 'nullable|string|max:500',
        ]);

        $approval = $this->approvalService->approve($id, $request->remarks);
        
        return response()->json([
            'success' => true,
            'data' => $approval,
            'message' => 'Clearance approved successfully'
        ]);
    }

    public function reject($id, Request $request)
    {
        $request->validate([
            'remarks' => 'required|string|min:5|max:500',
        ]);

        $approval = $this->approvalService->reject($id, $request->remarks);
        
        return response()->json([
            'success' => true,
            'data' => $approval,
            'message' => 'Clearance rejected successfully'
        ]);
    }

    public function getPendingApprovals(Request $request)
    {
        $departmentId = Auth::user()->assignedDepartments->first()->id ?? null;
        
        if (!$departmentId) {
            return response()->json([
                'success' => false,
                'message' => 'No department assigned'
            ], 403);
        }

        $pendingApprovals = $this->approvalService->getDepartmentPendingApprovals($departmentId);
        
        return response()->json([
            'success' => true,
            'data' => $pendingApprovals,
            'message' => 'Pending approvals retrieved successfully'
        ]);
    }

    public function studentStats($studentId)
    {
        $stats = $this->clearanceService->getStudentStats($studentId);
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Student statistics retrieved successfully'
        ]);
    }

    public function departmentStats($departmentId)
    {
        $stats = [
            'total' => ClearanceApproval::where('department_id', $departmentId)->count(),
            'pending' => ClearanceApproval::where('department_id', $departmentId)->where('status', 'pending')->count(),
            'approved' => ClearanceApproval::where('department_id', $departmentId)->where('status', 'approved')->count(),
            'rejected' => ClearanceApproval::where('department_id', $departmentId)->where('status', 'rejected')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Department statistics retrieved successfully'
        ]);
    }

    public function registrarStats()
    {
        if (!Auth::user()->hasRole('registrar')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $stats = [
            'total_requests' => ClearanceRequest::count(),
            'pending' => ClearanceRequest::whereIn('status', ['pending', 'in_progress'])->count(),
            'approved' => ClearanceRequest::where('status', 'approved')->count(),
            'completed' => ClearanceRequest::where('status', 'completed')->count(),
            'rejected' => ClearanceRequest::where('status', 'rejected')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Registrar statistics retrieved successfully'
        ]);
    }

    public function adminStats()
    {
        if (!Auth::user()->hasRole('super_admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_students' => \App\Models\User::role('student')->count(),
            'total_officers' => \App\Models\User::role('department_officer')->count(),
            'total_clearances' => ClearanceRequest::count(),
            'completed_clearances' => ClearanceRequest::where('status', 'completed')->count(),
            'active_departments' => \App\Models\Department::where('is_active', true)->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Admin statistics retrieved successfully'
        ]);
    }

    public function getDepartments()
    {
        $departments = \App\Models\Department::where('is_active', true)
            ->select('id', 'name', 'slug')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $departments,
            'message' => 'Departments retrieved successfully'
        ]);
    }

    public function getClearanceTypes()
    {
        $types = [
            'graduation' => 'Graduation',
            'withdrawal' => 'Withdrawal',
            'transfer' => 'Transfer',
            'dismissal' => 'Dismissal',
            'temporary_leave' => 'Temporary Leave',
            'semester_completion' => 'Semester Completion',
        ];
        
        return response()->json([
            'success' => true,
            'data' => $types,
            'message' => 'Clearance types retrieved successfully'
        ]);
    }

    public function checkStatus($reference)
    {
        $clearance = ClearanceRequest::where('reference_no', $reference)
            ->with(['student', 'approvals.department'])
            ->first();
        
        if (!$clearance) {
            return response()->json([
                'success' => false,
                'message' => 'Clearance not found'
            ], 404);
        }
        
        $total = $clearance->approvals->count();
        $approved = $clearance->approvals->where('status', 'approved')->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'reference_no' => $clearance->reference_no,
                'status' => $clearance->status,
                'progress' => $total > 0 ? round(($approved / $total) * 100) : 0,
                'student_name' => $clearance->student->full_name,
                'submitted_date' => $clearance->created_at->format('Y-m-d H:i:s'),
            ],
            'message' => 'Status retrieved successfully'
        ]);
    }
}