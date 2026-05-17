<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfService
{
    /**
     * Generate clearance certificate PDF
     */
    public function generateClearanceCertificate($clearance)
    {
        $qrSvg = QrCode::format('svg')->size(200)->generate($clearance->reference_no);
        $qrCode = base64_encode($qrSvg);
        
        $pdf = Pdf::loadView('pdf.clearance-certificate', [
            'clearance' => $clearance,
            'student' => $clearance->student,
            'approvals' => $clearance->approvals->load('department'),
            'qrCode' => $qrCode,
            'university_name' => $this->getUniversityName(),
            'generated_date' => now()->format('F d, Y'),
        ]);
        
        $pdf->setPaper('A4', 'portrait');
        
        $filename = "clearance_{$clearance->reference_no}_{$clearance->student->student_id}.pdf";
        $path = "clearances/{$filename}";
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return [
            'path' => $path,
            'filename' => $filename,
            'url' => Storage::disk('public')->url($path),
            'content' => $pdf->output(),
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
        
        $filename = "report_{$title}_{date('Ymd_His')}.pdf";
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
}