<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\ClearanceService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $clearanceService;

    public function __construct(ClearanceService $clearanceService)
    {
        $this->clearanceService = $clearanceService;
    }

    public function index()
    {
        $student = Auth::user()->student;
        $stats = $this->clearanceService->getStudentStats($student->id);
        $recentClearances = $this->clearanceService->getStudentClearances($student->id)->take(5);
        
        return view('student.dashboard', compact('stats', 'recentClearances'));
    }
}