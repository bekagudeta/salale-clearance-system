<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\ClearanceRequest;
use App\Services\ClearanceService;
use App\Services\PdfService;
use App\Services\NotificationService;
use App\Events\ClearanceCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClearanceController extends Controller
{
    protected $clearanceService;
    protected $pdfService;
    protected $notificationService;

    public function __construct(
        ClearanceService $clearanceService,
        PdfService $pdfService,
        NotificationService $notificationService
    ) {
        $this->clearanceService = $clearanceService;
        $this->pdfService = $pdfService;
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $query = ClearanceRequest::with(['student', 'approvals.department']);
        
        // Filter by status
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by type
        if ($request->type && $request->type != 'all') {
            $query->where('type', $request->type);
        }
        
        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('reference_no', 'like', "%{$request->search}%")
                  ->orWhereHas('student', function($sq) use ($request) {
                      $sq->where('student_id', 'like', "%{$request->search}%")
                        ->orWhere('full_name', 'like', "%{$request->search}%");
                  });
            });
        }
        
        $clearances = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('registrar.clearance.index', compact('clearances', 'request'));
    }

    public function show($id)
    {
        $clearance = $this->clearanceService->getClearanceDetails($id);
        
        $allApproved = $clearance->approvals->every(function($approval) {
            return $approval->status === 'approved';
        });
        
        return view('registrar.clearance.show', compact('clearance', 'allApproved'));
    }

    public function finalize($id)
    {
        $clearance = ClearanceRequest::with(['student.user', 'approvals'])->findOrFail($id);
        
        // Check if all departments have approved
        $allApproved = $clearance->approvals->every(function($approval) {
            return $approval->status === 'approved';
        });
        
        if (!$allApproved) {
            return redirect()->back()->with('error', 'Cannot finalize: Not all departments have approved.');
        }
        
        DB::transaction(function() use ($clearance) {
            $clearance->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            
            // Generate PDF certificate
            $pdfData = $this->pdfService->generateClearanceCertificate($clearance);
            
            // Store PDF path
            $clearance->update(['certificate_path' => $pdfData['path']]);
            
            // Trigger completion event
            event(new ClearanceCompleted($clearance));
            
            // Notify student
            $this->notificationService->notifyCompletion($clearance->student->user, $clearance);
        });
        
        return redirect()->route('registrar.clearance.show', $clearance->id)
            ->with('success', 'Clearance finalized successfully. Certificate generated.');
    }

    public function downloadCertificate($id)
    {
        $clearance = ClearanceRequest::with(['student'])->findOrFail($id);
        
        if ($clearance->status !== 'completed') {
            abort(403, 'Certificate not available yet.');
        }
        
        $pdfData = $this->pdfService->generateClearanceCertificate($clearance);
        
        return response()->download(storage_path("app/public/{$pdfData['path']}"), $pdfData['filename']);
    }
}