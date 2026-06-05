<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StudentImportTemplateExport;
use App\Http\Controllers\Controller;
use App\Services\StudentImportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentImportController extends Controller
{
    public function __construct(
        protected StudentImportService $importService
    ) {}

    public function show()
    {
        return view('admin.users.import');
    }

    public function template()
    {
        $filename = 'student_import_template_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new StudentImportTemplateExport, $filename);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:5120',
        ]);

        $result = $this->importService->parseFile($request->file('file'));

        if (! empty($result['error'])) {
            return redirect()
                ->route('admin.users.import')
                ->with('error', $result['error']);
        }

        if ($result['summary']['total'] === 0) {
            return redirect()
                ->route('admin.users.import')
                ->with('error', 'No student rows were found in the file.');
        }

        session(['student_import_preview' => $result]);

        return redirect()->route('admin.users.import.preview');
    }

    public function previewPage()
    {
        $preview = session('student_import_preview');

        if (! $preview) {
            return redirect()
                ->route('admin.users.import')
                ->with('error', 'Upload a file first to preview students.');
        }

        return view('admin.users.import-preview', [
            'preview' => $preview,
        ]);
    }

    public function process(Request $request)
    {
        $preview = session('student_import_preview');

        if (! $preview) {
            return redirect()
                ->route('admin.users.import')
                ->with('error', 'Import session expired. Please upload your file again.');
        }

        $result = $this->importService->importRows($preview['rows']);
        session()->forget('student_import_preview');

        $message = "{$result['imported']} student(s) imported successfully.";

        if ($result['skipped'] > 0) {
            $message .= " {$result['skipped']} invalid row(s) were skipped.";
        }

        if (count($result['failed']) > 0) {
            $message .= ' ' . count($result['failed']) . ' row(s) failed during import.';

            return redirect()
                ->route('admin.users.index')
                ->with('warning', $message)
                ->with('import_failures', $result['failed']);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', $message);
    }

    public function cancel()
    {
        session()->forget('student_import_preview');

        return redirect()
            ->route('admin.users.import')
            ->with('success', 'Import cancelled.');
    }
}
