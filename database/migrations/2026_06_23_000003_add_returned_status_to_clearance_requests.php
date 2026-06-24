<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'returned' — used when an academic head sends a request back to
        // the student to fix and resubmit (rather than rejecting outright).
        DB::statement("ALTER TABLE clearance_requests MODIFY COLUMN status ENUM('pending', 'in_progress', 'approved', 'rejected', 'returned', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Park any returned requests back to pending before dropping the value.
        DB::statement("UPDATE clearance_requests SET status = 'pending' WHERE status = 'returned'");
        DB::statement("ALTER TABLE clearance_requests MODIFY COLUMN status ENUM('pending', 'in_progress', 'approved', 'rejected', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
