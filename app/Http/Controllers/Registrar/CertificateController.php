<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\ClearanceRequest;
use App\Services\PdfService;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function index(Request $request)
    {
        $query = ClearanceRequest::where('status', 'completed')
            ->with(['student']);
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('reference_no', 'like', "%{$request->search}%")
                  ->orWhereHas('student', function($sq) use ($request) {
                      $sq->where('student_id', 'like', "%{$request->search}%")
                        ->orWhere('full_name', 'like', "%{$request->search}%");
                  });
            });
        }
        
        $certificates = $query->orderBy('completed_at', 'desc')
            ->paginate(20);
        
        return view('registrar.certificates.index', compact('certificates'));
    }

    public function regenerate($id)
    {
        $clearance = ClearanceRequest::findOrFail($id);
        
        if ($clearance->status !== 'completed') {
            return redirect()->back()->with('error', 'Certificate can only be regenerated for completed clearances.');
        }
        
        $pdfData = $this->pdfService->generateClearanceCertificate($clearance);
        
        $clearance->update(['certificate_path' => $pdfData['path']]);
        
        return redirect()->back()->with('success', 'Certificate regenerated successfully.');
    }

    public function verify($reference)
    {
        $clearance = ClearanceRequest::where('reference_no', $reference)
            ->with(['student', 'approvals.department'])
            ->firstOrFail();
        
        $isValid = $clearance->status === 'completed';
        
        return view('public.verify', compact('clearance', 'isValid'));
    }
}