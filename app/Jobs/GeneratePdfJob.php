<?php

namespace App\Jobs;

use App\Models\ClearanceRequest;
use App\Helpers\PdfHelper;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clearanceId;

    public function __construct($clearanceId)
    {
        $this->clearanceId = $clearanceId;
    }

    public function handle(NotificationService $notificationService)
    {
        $clearance = ClearanceRequest::with(['student.user'])->findOrFail($this->clearanceId);
        
        $pdfData = PdfHelper::generateClearanceCertificate($clearance);
        
        $notificationService->createDatabaseNotification(
            $clearance->student->user_id,
            'Certificate Generated',
            "Your clearance certificate has been generated. Reference: {$clearance->reference_no}",
            'certificate'
        );
        
        \Log::info("PDF generated for clearance: {$clearance->reference_no}", $pdfData);
    }
}