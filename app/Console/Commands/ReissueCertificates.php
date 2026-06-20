<?php

namespace App\Console\Commands;

use App\Models\CertificateAudit;
use App\Models\ClearanceRequest;
use App\Services\PdfService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ReissueCertificates extends Command
{
    /**
     * @var string
     */
    protected $signature = 'certificates:reissue
                            {--dry-run : List what would be re-issued without writing any files}
                            {--id=* : Only re-issue specific clearance request id(s)}';

    /**
     * @var string
     */
    protected $description = 'Re-generate completed clearance certificates under the current security scheme (no open password, owner-locked, QR verification).';

    public function handle(PdfService $pdfService): int
    {
        $query = ClearanceRequest::where('status', 'completed')
            ->with(['student.user', 'approvals.department']);

        if ($ids = $this->option('id')) {
            $query->whereIn('id', $ids);
        }

        $clearances = $query->orderBy('completed_at')->get();

        if ($clearances->isEmpty()) {
            $this->info('No completed certificates found to re-issue.');
            return self::SUCCESS;
        }

        $this->info("Found {$clearances->count()} completed certificate(s).");

        if ($this->option('dry-run')) {
            $this->table(
                ['ID', 'Reference', 'Student', 'Completed', 'Current file'],
                $clearances->map(fn ($c) => [
                    $c->id,
                    $c->reference_no,
                    optional($c->student)->student_id ?? '—',
                    optional($c->completed_at)->format('Y-m-d') ?? '—',
                    $c->certificate_path ?? '(none)',
                ])->all()
            );
            $this->comment('Dry run — nothing was written. Re-run without --dry-run to apply.');
            return self::SUCCESS;
        }

        if (! $this->confirm("Re-issue {$clearances->count()} certificate(s)? Old copies already distributed will still verify.", true)) {
            $this->warn('Aborted.');
            return self::SUCCESS;
        }

        $succeeded = 0;
        $failed = 0;
        $bar = $this->output->createProgressBar($clearances->count());
        $bar->start();

        foreach ($clearances as $clearance) {
            try {
                // Remove the stale file so we don't leave the old-scheme PDF behind.
                if ($clearance->certificate_path && Storage::disk('public')->exists($clearance->certificate_path)) {
                    Storage::disk('public')->delete($clearance->certificate_path);
                }

                $pdfData = $pdfService->generateClearanceCertificate($clearance);
                $clearance->update(['certificate_path' => $pdfData['path']]);

                CertificateAudit::create([
                    'clearance_id'  => $clearance->id,
                    'user_id'       => null,
                    'ip_address'    => 'console',
                    'action'        => 'reissue',
                    'security_code' => $pdfData['security_code'],
                    'issued_date'   => $pdfData['issued_date'],
                    'validity_date' => $pdfData['validity_date'],
                    'issued_by'     => 'System (certificates:reissue)',
                    'timestamp'     => now(),
                ]);

                $succeeded++;
            } catch (\Throwable $e) {
                $failed++;
                $this->newLine();
                $this->error("Failed #{$clearance->id} ({$clearance->reference_no}): {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Re-issued: {$succeeded}" . ($failed ? " | Failed: {$failed}" : ''));

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
