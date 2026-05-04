<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfService
{
    public function generateClearanceCertificate($clearance)
    {
        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($clearance->reference_no));
        
        $pdf = Pdf::loadView('pdf.clearance-certificate', [
            'clearance' => $clearance,
            'student' => $clearance->student,
            'approvals' => $clearance->approvals,
            'qrCode' => $qrCode,
        ]);
        
        $filename = "clearance_{$clearance->reference_no}.pdf";
        $path = "clearances/{$filename}";
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return [
            'path' => $path,
            'filename' => $filename,
        ];
    }
}