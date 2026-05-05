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
}