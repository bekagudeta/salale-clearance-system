<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreClearanceRequest;
use App\Services\ClearanceService;
use App\Services\PdfService;
use Illuminate\Support\Facades\Auth;

class ClearanceController extends Controller
{
    protected $clearanceService;
    protected $pdfService;

    public function __construct(ClearanceService $clearanceService, PdfService $pdfService)
    {
        $this->clearanceService = $clearanceService;
        $this->pdfService = $pdfService;
    }

    public function create()
    {
        return view('student.clearance.create');
    }

    public function store(StoreClearanceRequest $request)
    {
        $student = Auth::user()->student;

        try {
            $clearance = $this->clearanceService->createClearance($request->validated(), $student->id);
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('student.clearance.show', $clearance->id)
            ->with('success', 'Clearance request submitted to your department head.');
    }

    public function resubmit(\Illuminate\Http\Request $request, $id)
    {
        $student = Auth::user()->student;

        $data = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->clearanceService->resubmitClearance($id, $student->id, $data);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('student.clearance.show', $id)
            ->with('success', 'Clearance request resubmitted to your department head.');
    }

    public function show($id)
    {
        $clearance = $this->clearanceService->getClearanceDetails($id);
        
        if ($clearance->student_id !== Auth::user()->student->id) {
            abort(403);
        }
        
        return view('student.clearance.show', compact('clearance'));
    }

    public function cancel($id)
    {
        $student = Auth::user()->student;
        $this->clearanceService->cancelClearance($id, $student->id);

        return redirect()->route('student.clearance.history')
            ->with('success', 'Clearance request cancelled.');
    }

    public function download($id)
    {
        $clearance = $this->clearanceService->getClearanceDetails($id);
        $student = Auth::user()->student;

        if ($clearance->student_id !== $student->id) {
            abort(403);
        }

        if ($clearance->status !== 'completed') {
            abort(403, 'Certificate not available yet.');
        }

        if (!$clearance->certificate_path || !file_exists(storage_path("app/public/{$clearance->certificate_path}"))) {
            $pdfData = $this->pdfService->generateClearanceCertificate($clearance);
            $clearance->update(['certificate_path' => $pdfData['path']]);
        } else {
            $pdfData = [
                'path' => $clearance->certificate_path,
                'filename' => pathinfo($clearance->certificate_path, PATHINFO_BASENAME),
            ];
        }

        $downloadName = pathinfo($pdfData['path'], PATHINFO_BASENAME);

        return response()->download(storage_path("app/public/{$pdfData['path']}"), $downloadName);
    }

    public function history()
    {
        $student = Auth::user()->student;
        $clearances = $this->clearanceService->getStudentClearances($student->id);
        
        return view('student.clearance.history', compact('clearances'));
    }
}