<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreClearanceRequest;
use App\Services\ClearanceService;
use Illuminate\Support\Facades\Auth;

class ClearanceController extends Controller
{
    protected $clearanceService;

    public function __construct(ClearanceService $clearanceService)
    {
        $this->clearanceService = $clearanceService;
    }

    public function create()
    {
        return view('student.clearance.create');
    }

    public function store(StoreClearanceRequest $request)
    {
        $student = Auth::user()->student;
        
        $clearance = $this->clearanceService->createClearance($request->validated(), $student->id);
        
        return redirect()->route('student.clearance.show', $clearance->id)
            ->with('success', 'Clearance request submitted successfully.');
    }

    public function show($id)
    {
        $clearance = $this->clearanceService->getClearanceDetails($id);
        
        if ($clearance->student_id !== Auth::user()->student->id) {
            abort(403);
        }
        
        return view('student.clearance.show', compact('clearance'));
    }

    public function history()
    {
        $student = Auth::user()->student;
        $clearances = $this->clearanceService->getStudentClearances($student->id);
        
        return view('student.clearance.history', compact('clearances'));
    }
}