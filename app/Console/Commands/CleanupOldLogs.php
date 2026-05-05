<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class CleanupOldLogs extends Command
{
    protected $signature = 'logs:cleanup {--days=90 : Number of days to keep logs}';
    protected $description = 'Delete activity logs older than specified days';

    public function handle()
    {
        $days = $this->option('days');
        $date = now()->subDays($days);
        
        $deleted = ActivityLog::where('created_at', '<', $date)->delete();
        
        $this->info("Deleted {$deleted} log records older than {$days} days.");
        
        return Command::SUCCESS;
    }
}