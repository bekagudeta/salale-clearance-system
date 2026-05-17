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

    public function export(Request $request, $type)
    {
        if ($request->query('format') !== 'csv') {
            abort(400, 'Only CSV export is supported.');
        }

        switch ($type) {
            case 'clearances':
                $records = $this->reportService->exportClearances();
                $filename = 'clearances_export_' . now()->format('Ymd_His') . '.csv';
                $rows = [];
                $rows[] = ['Reference No', 'Student Name', 'Student ID', 'Type', 'Status', 'Submitted At', 'Completed At'];
                foreach ($records as $record) {
                    $rows[] = [
                        $record->reference_no,
                        optional($record->student)->full_name,
                        optional($record->student)->student_id,
                        ucfirst(str_replace('_', ' ', $record->type)),
                        ucfirst($record->status),
                        $record->created_at ? $record->created_at->toDateTimeString() : '',
                        $record->completed_at ? $record->completed_at->toDateTimeString() : '',
                    ];
                }
                break;

            case 'students':
                $records = $this->reportService->exportStudents();
                $filename = 'students_export_' . now()->format('Ymd_His') . '.csv';
                $rows = [];
                $rows[] = ['Student ID', 'Full Name', 'Faculty', 'Department', 'Year', 'Semester', 'Phone', 'Gender', 'Email'];
                foreach ($records as $record) {
                    $rows[] = [
                        $record->student_id,
                        $record->full_name,
                        $record->faculty,
                        $record->department,
                        $record->year,
                        $record->semester,
                        $record->phone,
                        $record->gender,
                        optional($record->user)->email,
                    ];
                }
                break;

            case 'departments':
                $records = $this->reportService->exportDepartments();
                $filename = 'departments_export_' . now()->format('Ymd_His') . '.csv';
                $rows = [];
                $rows[] = ['Department Name', 'Slug', 'Officer Name', 'Officer Email', 'Priority Order', 'Active'];
                foreach ($records as $record) {
                    $rows[] = [
                        $record->name,
                        $record->slug,
                        optional($record->officer)->name,
                        optional($record->officer)->email,
                        $record->priority_order,
                        $record->is_active ? 'Yes' : 'No',
                    ];
                }
                break;

            default:
                abort(404);
        }

        $handle = fopen('php://temp', 'w+');
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
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
            $pdfData = $this->reportService->formatReportRows($request->report_type, $data);
            $pdf = PdfHelper::generateReport($pdfData, $title);
            return response()->download(storage_path("app/public/{$pdf['path']}"), $pdf['filename']);
        }
        
        return view('registrar.reports.result', compact('data', 'title'));
    }
}