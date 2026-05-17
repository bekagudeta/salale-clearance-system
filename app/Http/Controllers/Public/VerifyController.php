<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ClearanceRequest;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function index()
    {
        return view('public.verify-form');
    }

    public function check(Request $request)
    {
        $request->validate([
            'reference_no' => 'required|string',
        ]);
        
        $clearance = ClearanceRequest::where('reference_no', $request->reference_no)
            ->with(['student', 'approvals.department'])
            ->first();
        
        if (!$clearance) {
            return redirect()->back()->with('error', 'Invalid reference number.');
        }
        
        $isValid = $clearance->status === 'completed';
        
        return view('public.verify-result', compact('clearance', 'isValid'));
    }

    public function show($reference)
    {
        $clearance = ClearanceRequest::where('reference_no', $reference)
            ->with(['student', 'approvals.department'])
            ->firstOrFail();
        
        $isValid = $clearance->status === 'completed';
        
        return view('public.verify-result', compact('clearance', 'isValid'));
    }

    public function apiVerify($reference)
    {
        try {
            $clearance = ClearanceRequest::where('reference_no', $reference)
                ->with(['student', 'approvals.department'])
                ->firstOrFail();
            
            $isValid = $clearance->status === 'completed';
            
            if (!$isValid) {
                return response()->json([
                    'valid' => false,
                    'message' => 'This clearance is not yet completed.',
                ], 400);
            }
            
            return response()->json([
                'valid' => true,
                'reference_no' => $clearance->reference_no,
                'student_name' => $clearance->student->full_name,
                'student_id' => $clearance->student->student_id,
                'type' => ucfirst(str_replace('_', ' ', $clearance->type)),
                'status' => ucfirst($clearance->status),
                'completed_date' => $clearance->completed_at?->toDateString(),
                'approvals' => $clearance->approvals->map(function ($approval) {
                    return [
                        'department' => $approval->department->name,
                        'status' => ucfirst($approval->status),
                        'date' => $approval->approved_at?->toDateString(),
                    ];
                }),
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Certificate not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'An error occurred while verifying the certificate.',
            ], 500);
        }
    }
}