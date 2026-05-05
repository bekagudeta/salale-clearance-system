<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Helpers\PdfHelper;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('registrar.reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:cleared_students,rejected_requests,department_delays,graduation_stats',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:2100',
        ]);
        
        $data = [];
        $title = '';
        
        switch ($request->report_type) {
            case 'cleared_students':
                $month = $request->month ?? date('m');
                $year = $request->year ?? date('Y');
                $data = $this->reportService->getClearedStudentsByMonth($month, $year);
                $title = "Cleared Students Report - {$month}/{$year}";
                break;
                
            case 'rejected_requests':
                $fromDate = $request->from_date ?? now()->subDays(30);
                $toDate = $request->to_date ?? now();
                $data = $this->reportService->getRejectedRequests($fromDate, $toDate);
                $title = "Rejected Requests Report";
                break;
                
            case 'department_delays':
                $data = $this->reportService->getDepartmentDelays();
                $title = "Department Delays Report";
                break;
                
            case 'graduation_stats':
                $year = $request->year ?? date('Y');
                $data = $this->reportService->getGraduationStatistics($year);
                $title = "Graduation Statistics - {$year}";
                break;
        }
        
        if ($request->format === 'pdf') {
            $pdf = PdfHelper::generateReport($data, $title);
            return response()->download(storage_path("app/public/{$pdf['path']}"), $pdf['filename']);
        }
        
        return view('registrar.reports.result', compact('data', 'title'));
    }
}