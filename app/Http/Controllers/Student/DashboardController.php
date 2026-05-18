<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\ClearanceService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $clearanceService;
    protected $notificationService;

    public function __construct(
        ClearanceService $clearanceService,
        NotificationService $notificationService
    ) {
        $this->clearanceService = $clearanceService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $user = Auth::user();
        $student = $user?->student;

        if (!$user || !$student) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your session or student profile could not be validated. Please sign in again or contact support.');
        }

        $stats = $this->clearanceService->getStudentStats($student->id);
        $recentClearances = $this->clearanceService->getStudentClearances($student->id)->take(5);
        $unreadNotifications = $user->notifications()->where('is_read', false)->count();
        
        return view('student.dashboard', compact('stats', 'recentClearances', 'unreadNotifications'));
    }
}