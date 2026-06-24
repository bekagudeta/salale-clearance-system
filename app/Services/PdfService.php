<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\CertificateAudit;
use Illuminate\Support\Str;

class PdfService
{
    /**
     * Generate clearance certificate PDF with security features
     */
    public function generateClearanceCertificate($clearance)
    {
        // Generate security code and timestamp. Anchor the issue date to when the
        // clearance was completed so re-issued certificates keep their original date
        // (falls back to now() for a fresh finalization where completed_at is set).
        $securityCode = $this->generateSecurityCode($clearance);
        $issuedDate = $clearance->completed_at ? $clearance->completed_at->copy() : now();
        $validityDate = $issuedDate->copy()->addYears(4); // Valid for 4 years
        
        // Encode the QR as a verification URL carrying the security code, so a scan
        // lands on the public verifier with full proof (not just the reference).
        // The reference and code go in the QUERY STRING — never the path — because the
        // reference contains slashes and Apache rejects encoded slashes (%2F) in paths.
        $verifyUrl = url('/verify/lookup') . '?ref=' . urlencode($clearance->reference_no) . '&code=' . urlencode($securityCode);

        $qrSvg = QrCode::format('svg')->size(200)->errorCorrection('H')->generate($verifyUrl);
        $qrCode = base64_encode($qrSvg);

        $pdf = Pdf::loadView('pdf.clearance-certificate', [
            'clearance' => $clearance,
            'student' => $clearance->student,
            'approvals' => $clearance->approvals->load('department', 'officer'),
            'qrCode' => $qrCode,
            'verify_url' => $verifyUrl,
            'logo_path' => $this->getLogoPath(),
            'university_name' => $this->getUniversityName(),
            'generated_date' => $issuedDate->format('F d, Y'),
            'issued_datetime' => $issuedDate->format('F d, Y H:i:s'),
            'validity_date' => $validityDate->format('F d, Y'),
            'security_code' => $securityCode,
            'document_hash' => $this->generateDocumentHash($clearance, $securityCode),
        ]);
        
        $pdf->setPaper('A4', 'portrait');
        
        // Certificate security:
        //  - No open (user) password, so students and verifiers can open/print it freely.
        //  - A strong owner password locks the document so it cannot be edited, annotated,
        //    or have its text copied (only printing is permitted) — making it tamper-evident.
        //  - Authenticity is proven by the QR code + public verification page, not a secret.
        // The owner password is never shared; it falls back to an app-key-derived value so
        // there is no hardcoded secret in source.
        $ownerPassword = env('PDF_OWNER_PASSWORD') ?: hash('sha256', config('app.key') . '|certificate-owner');
        $pdf->setEncryption('', $ownerPassword, ['print']);
        
        $safeReference = preg_replace('/[^A-Za-z0-9_\-]/', '_', $clearance->reference_no);
        $filename = "clearance_{$safeReference}_{$clearance->student->student_id}.pdf";
        $path = "clearances/{$filename}";
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return [
            'path' => $path,
            'filename' => $filename,
            'url' => Storage::disk('public')->url($path),
            'content' => $pdf->output(),
            'security_code' => $securityCode,
            'issued_date' => $issuedDate,
            'validity_date' => $validityDate,
        ];
    }

    /**
     * Generate report PDF
     */
    public function generateReport($data, $title, $type = 'standard')
    {
        $view = 'pdf.report-' . $type;
        
        if (!view()->exists($view)) {
            $view = 'pdf.report';
        }
        
        $pdf = Pdf::loadView($view, [
            'data' => $data,
            'title' => $title,
            'generated_date' => now()->format('F d, Y H:i:s'),
            'generated_by' => auth()->user()->name ?? 'System',
        ]);
        
        $pdf->setPaper('A4', 'landscape');
        
        $filename = "report_{$title}_" . date('Ymd_His') . ".pdf";
        $path = "reports/{$filename}";
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return [
            'path' => $path,
            'filename' => $filename,
            'url' => Storage::disk('public')->url($path),
        ];
    }

    /**
     * Generate multiple certificates in bulk
     */
    public function generateBulkCertificates(array $clearanceIds)
    {
        $results = [];
        
        foreach ($clearanceIds as $clearanceId) {
            try {
                $clearance = \App\Models\ClearanceRequest::with(['student', 'approvals.department'])
                    ->findOrFail($clearanceId);
                    
                if ($clearance->status === 'completed') {
                    $results[] = $this->generateClearanceCertificate($clearance);
                }
            } catch (\Exception $e) {
                $results[] = ['error' => $e->getMessage(), 'clearance_id' => $clearanceId];
            }
        }
        
        return $results;
    }

    /**
     * Stream PDF to browser
     */
    public function streamPdf($view, $data, $filename = 'document.pdf')
    {
        $pdf = Pdf::loadView($view, $data);
        return $pdf->stream($filename);
    }

    /**
     * Download PDF
     */
    public function downloadPdf($view, $data, $filename = 'document.pdf')
    {
        $pdf = Pdf::loadView($view, $data);
        return $pdf->download($filename);
    }

    /**
     * Generate QR code as base64
     */
    public function generateQrCode($text, $size = 200)
    {
        return base64_encode(QrCode::format('svg')->size($size)->generate($text));
    }

    /**
     * Get university name from settings
     */
    private function getUniversityName()
    {
        $setting = \App\Models\Setting::where('key', 'university_name')->first();
        return $setting ? $setting->value : 'Salale University';
    }

    /**
     * University logo as a base64 data URI for safe embedding in the PDF, or null
     * if none is configured / the file is missing. A data URI sidesteps DomPDF's
     * file-path and remote-URL restrictions entirely.
     */
    private function getLogoPath()
    {
        $setting = \App\Models\Setting::where('key', 'university_logo')->first();

        if ($setting && $setting->value) {
            $path = Storage::disk('public')->path($setting->value);
            if (is_file($path)) {
                $mime = mime_content_type($path) ?: 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        return null;
    }

    /**
     * Merge multiple PDFs
     */
    public function mergePdfs(array $pdfPaths, $outputFilename)
    {
        // This requires additional PDF manipulation library
        // Example using setasign/fpdi
        $pdf = new \setasign\Fpdi\Fpdi();
        
        foreach ($pdfPaths as $path) {
            $pageCount = $pdf->setSourceFile(Storage::disk('public')->path($path));
            for ($i = 1; $i <= $pageCount; $i++) {
                $template = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($template);
                $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                $pdf->useTemplate($template);
            }
        }
        
        $outputPath = "merged/{$outputFilename}";
        Storage::disk('public')->put($outputPath, $pdf->Output('S'));
        
        return [
            'path' => $outputPath,
            'filename' => $outputFilename,
        ];
    }

    /**
     * Generate unique security code for certificate
     */
    private function generateSecurityCode($clearance)
    {
        $timestamp = now()->format('YmdHis');
        $referenceHash = substr(hash('sha256', $clearance->reference_no), 0, 8);
        $randomCode = strtoupper(Str::random(6));
        
        return "{$timestamp}-{$referenceHash}-{$randomCode}";
    }

    /**
     * Generate document hash for verification
     */
    private function generateDocumentHash($clearance, $securityCode)
    {
        $data = implode('|', [
            $clearance->reference_no,
            $clearance->student->student_id,
            $clearance->type,
            $clearance->status,
            $securityCode,
        ]);
        
        return strtoupper(substr(hash('sha256', $data), 0, 16));
    }

    /**
     * Verify certificate integrity
     */
    public function verifyCertificate($referenceNo, $securityCode)
    {
        $clearance = \App\Models\ClearanceRequest::where('reference_no', $referenceNo)->first();
        
        if (!$clearance) {
            return [
                'valid' => false,
                'message' => 'Certificate not found',
            ];
        }

        if ($clearance->status !== 'completed') {
            return [
                'valid' => false,
                'message' => 'Certificate not yet completed',
            ];
        }

        // Get audit record
        $audit = CertificateAudit::where('clearance_id', $clearance->id)
            ->where('security_code', $securityCode)
            ->first();

        if (!$audit) {
            return [
                'valid' => false,
                'message' => 'Invalid security code',
            ];
        }

        // Check validity date
        if ($audit->validity_date && $audit->validity_date->isPast()) {
            return [
                'valid' => false,
                'message' => 'Certificate has expired',
                'expired_date' => $audit->validity_date->format('F d, Y'),
            ];
        }

        return [
            'valid' => true,
            'message' => 'Certificate is valid',
            'clearance' => $clearance,
            'issued_date' => $audit->issued_date,
            'validity_date' => $audit->validity_date,
            'issued_by' => $audit->issued_by,
        ];
    }

    /**
     * Log certificate download
     */
    public function logCertificateDownload($clearanceId, $userId, $ipAddress)
    {
        CertificateAudit::create([
            'clearance_id' => $clearanceId,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'action' => 'download',
            'timestamp' => now(),
        ]);
    }
}