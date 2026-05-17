<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfHelper
{
    public static function generateClearanceCertificate($clearance)
    {
        $qrSvg = QrCode::format('svg')->size(200)->generate($clearance->reference_no);
        $qrCode = base64_encode($qrSvg);
        
        $pdf = Pdf::loadView('pdf.clearance-certificate', [
            'clearance' => $clearance,
            'student' => $clearance->student,
            'approvals' => $clearance->approvals()->with('department')->get(),
            'qrCode' => $qrCode,
            'universityName' => 'Salale University',
            'universityLogo' => public_path('uploads/logos/logo.png'),
        ]);
        
        $filename = "clearance_{$clearance->reference_no}_{$clearance->student->student_id}.pdf";
        $path = "clearances/{$filename}";
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return [
            'path' => $path,
            'filename' => $filename,
            'url' => Storage::disk('public')->url($path),
        ];
    }
    
    public static function generateReport($data, $title)
    {
        $pdf = Pdf::loadView('pdf.report', [
            'data' => $data,
            'title' => $title,
            'generatedDate' => now()->format('F d, Y H:i:s'),
        ]);

        $pdf->setPaper('A4', 'landscape');
        
        $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $title);
        $safeTitle = preg_replace('/_+/', '_', $safeTitle);
        $safeTitle = trim($safeTitle, '_');
        
        $filename = "report_{$safeTitle}_" . now()->format('Ymd_His') . ".pdf";
        $path = "reports/{$filename}";
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return [
            'path' => $path,
            'filename' => $filename,
            'url' => Storage::disk('public')->url($path),
        ];
    }
    
    public static function streamPdf($view, $data)
    {
        $pdf = Pdf::loadView($view, $data);
        return $pdf->stream();
    }
    
    public static function downloadPdf($view, $data, $filename)
    {
        $pdf = Pdf::loadView($view, $data);
        return $pdf->download($filename);
    }
}