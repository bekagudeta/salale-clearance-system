<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\ClearanceRequest;
use App\Models\CertificateAudit;
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

    public function download($id)
    {
        $clearance = ClearanceRequest::findOrFail($id);
        
        if ($clearance->status !== 'completed') {
            abort(403, 'Certificate not available yet.');
        }

        if (!$clearance->certificate_path || !file_exists(storage_path("app/public/{$clearance->certificate_path}"))) {
            $pdfData = $this->pdfService->generateClearanceCertificate($clearance);
            $clearance->update(['certificate_path' => $pdfData['path']]);
            
            // Log certificate generation and save security info
            CertificateAudit::create([
                'clearance_id' => $clearance->id,
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
                'action' => 'generate',
                'security_code' => $pdfData['security_code'],
                'issued_date' => $pdfData['issued_date'],
                'validity_date' => $pdfData['validity_date'],
                'issued_by' => auth()->user()->name,
                'timestamp' => now(),
            ]);
        } else {
            $pdfData = [
                'path' => $clearance->certificate_path,
                'filename' => pathinfo($clearance->certificate_path, PATHINFO_BASENAME),
            ];
        }

        $downloadName = pathinfo($pdfData['path'], PATHINFO_BASENAME);
        
        // Log certificate download
        CertificateAudit::create([
            'clearance_id' => $clearance->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'action' => 'download',
            'timestamp' => now(),
        ]);

        return response()->download(storage_path("app/public/{$pdfData['path']}"), $downloadName);
    }

    public function regenerate($id)
    {
        $clearance = ClearanceRequest::findOrFail($id);
        
        if ($clearance->status !== 'completed') {
            return redirect()->back()->with('error', 'Certificate can only be regenerated for completed clearances.');
        }
        
        $pdfData = $this->pdfService->generateClearanceCertificate($clearance);
        
        $clearance->update(['certificate_path' => $pdfData['path']]);
        
        // Log certificate regeneration
        CertificateAudit::create([
            'clearance_id' => $clearance->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'action' => 'regenerate',
            'security_code' => $pdfData['security_code'],
            'issued_date' => $pdfData['issued_date'],
            'validity_date' => $pdfData['validity_date'],
            'issued_by' => auth()->user()->name,
            'timestamp' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Certificate regenerated successfully.');
    }

    public function verify($reference)
    {
        $clearance = ClearanceRequest::where('reference_no', $reference)
            ->with(['student', 'approvals.department'])
            ->firstOrFail();
        
        $isValid = $clearance->status === 'completed';
        
        // Log verification attempt
        if ($isValid) {
            CertificateAudit::create([
                'clearance_id' => $clearance->id,
                'ip_address' => request()->ip(),
                'action' => 'verify',
                'timestamp' => now(),
            ]);
        }
        
        return view('public.verify', compact('clearance', 'isValid'));
    }
}