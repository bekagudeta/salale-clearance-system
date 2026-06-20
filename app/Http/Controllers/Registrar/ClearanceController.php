<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\ClearanceApproval;
use App\Models\ClearanceRequest;
use App\Models\Department;
use App\Services\ApprovalService;
use App\Services\ClearanceService;
use App\Services\NotificationService;
use App\Services\PdfService;
use App\Events\ClearanceCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClearanceController extends Controller
{
    protected $clearanceService;
    protected $approvalService;
    protected $pdfService;
    protected $notificationService;

    public function __construct(
        ClearanceService $clearanceService,
        ApprovalService $approvalService,
        PdfService $pdfService,
        NotificationService $notificationService
    ) {
        $this->clearanceService = $clearanceService;
        $this->approvalService = $approvalService;
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

        // Ensure the clearance status is up to date before rendering
        $clearances->getCollection()->each(function ($clearance) {
            $clearance->updateStatusFromApprovals();
        });
        
        return view('registrar.clearance.index', compact('clearances', 'request'));
    }

    public function show($id)
    {
        $clearance = $this->clearanceService->getClearanceDetails($id);
        $clearance->updateStatusFromApprovals();
        $clearance->refresh();
        
        $allApproved = $clearance->hasAllRequiredApprovals();

        $registrarApproval = $clearance->approvals->first(function($approval) {
            return $approval->department && $approval->department->slug === 'registrar-office';
        });

        // The registrar is the final checkpoint: they may approve only once every
        // other active department has approved.
        $otherDepartmentsApproved = $clearance->relevantApprovals()
            ->filter(fn ($approval) => $approval->department->slug !== 'registrar-office')
            ->every(fn ($approval) => $approval->status === 'approved');

        $canApproveRegistrar = $registrarApproval
            && $registrarApproval->status === 'pending'
            && $otherDepartmentsApproved;

        return view('registrar.clearance.show', compact('clearance', 'allApproved', 'registrarApproval', 'canApproveRegistrar'));
    }

    public function approve($id)
    {
        $clearance = ClearanceRequest::with(['approvals.department', 'student.user'])->findOrFail($id);
        $registrarApproval = $clearance->approvals->first(function($approval) {
            return $approval->department && $approval->department->slug === 'registrar-office';
        });

        if (!$registrarApproval || $registrarApproval->status !== 'pending') {
            return redirect()->back()->with('error', 'No pending registrar approval found for this clearance.');
        }

        // Enforce registrar-approves-last: every other active department must have
        // approved before the registrar can record their approval.
        $pendingOther = $clearance->relevantApprovals()->first(function ($approval) {
            return $approval->department->slug !== 'registrar-office'
                && $approval->status !== 'approved';
        });

        if ($pendingOther) {
            return redirect()->back()->with('error', 'All other departments must approve before the registrar can approve.');
        }

        $this->approvalService->approve($registrarApproval->id);

        return redirect()->route('registrar.clearance.show', $clearance->id)
            ->with('success', 'Registrar approval recorded. The clearance is now ready for finalization.');
    }

    public function finalize($id)
    {
        $clearance = ClearanceRequest::with(['student.user', 'approvals.department'])->findOrFail($id);

        // Check that all active departments have approved (deactivated departments
        // are excluded so they can't block finalization).
        if (!$clearance->hasAllRequiredApprovals()) {
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

    public function print($id)
    {
        $clearance = ClearanceRequest::with(['student'])->findOrFail($id);
        
        if ($clearance->status !== 'completed') {
            abort(403, 'Certificate not available yet.');
        }
        
        $pdfData = $this->pdfService->generateClearanceCertificate($clearance);
        
        return response()->download(storage_path("app/public/{$pdfData['path']}"), $pdfData['filename']);
    }
}