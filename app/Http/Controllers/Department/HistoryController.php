<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ClearanceApproval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $department = Auth::user()->assignedDepartments->first() ?? Auth::user()->departments()->wherePivot('can_approve', true)->first();
        
        if (!$department) {
            return redirect()->route('home')->with('error', 'No department assigned.');
        }
        
        $query = ClearanceApproval::where('department_id', $department->id)
            ->with(['request.student']);
        
        // Filter by status
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $history = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $stats = [
            'total' => ClearanceApproval::where('department_id', $department->id)->count(),
            'approved' => ClearanceApproval::where('department_id', $department->id)
                ->where('status', 'approved')
                ->count(),
            'rejected' => ClearanceApproval::where('department_id', $department->id)
                ->where('status', 'rejected')
                ->count(),
            'pending' => ClearanceApproval::where('department_id', $department->id)
                ->where('status', 'pending')
                ->count(),
        ];
        
        return view('department.history', compact('history', 'stats', 'request'));
    }
    
    public function show($id)
    {
        $department = Auth::user()->assignedDepartments->first() ?? Auth::user()->departments()->wherePivot('can_approve', true)->first();
        
        $approval = ClearanceApproval::where('department_id', $department->id)
            ->where('id', $id)
            ->with(['request.student', 'request.approvals.department'])
            ->firstOrFail();
        
        return view('department.history.show', compact('approval'));
    }
}