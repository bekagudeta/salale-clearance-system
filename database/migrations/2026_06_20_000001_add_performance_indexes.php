<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds indexes tuned to the dashboard/list query patterns so the app stays fast
 * with tens of thousands of rows. Foreign keys are already indexed by
 * constrained(); these cover the status/timestamp columns used in filters,
 * aggregates, and ordering.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clearance_requests', function (Blueprint $table) {
            // Student dashboard: WHERE student_id + SUM(status='...').
            $table->index(['student_id', 'status'], 'cr_student_status_idx');
            // Registrar/admin: WHERE status, and SUM(status='...') over the whole table.
            $table->index('status', 'cr_status_idx');
            // Trend charts / recent lists: range + ORDER BY created_at.
            $table->index('created_at', 'cr_created_at_idx');
        });

        Schema::table('clearance_approvals', function (Blueprint $table) {
            // Department dashboard: WHERE department_id + status (pending queue, counts).
            $table->index(['department_id', 'status'], 'ca_dept_status_idx');
            // "Approved today" / processing-time: WHERE department_id + approved_at.
            $table->index(['department_id', 'approved_at'], 'ca_dept_approved_at_idx');
            // Officer's own processed list: WHERE approved_by + ORDER BY created_at.
            $table->index(['approved_by', 'created_at'], 'ca_approver_created_at_idx');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            // Recent-activity feeds: ORDER BY created_at DESC.
            $table->index('created_at', 'al_created_at_idx');
            // Student activity lookup by record_id.
            $table->index('record_id', 'al_record_id_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            // Unread badge / list: WHERE user_id + is_read.
            $table->index(['user_id', 'is_read'], 'notif_user_read_idx');
        });
    }

    public function down(): void
    {
        Schema::table('clearance_requests', function (Blueprint $table) {
            $table->dropIndex('cr_student_status_idx');
            $table->dropIndex('cr_status_idx');
            $table->dropIndex('cr_created_at_idx');
        });

        Schema::table('clearance_approvals', function (Blueprint $table) {
            $table->dropIndex('ca_dept_status_idx');
            $table->dropIndex('ca_dept_approved_at_idx');
            $table->dropIndex('ca_approver_created_at_idx');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('al_created_at_idx');
            $table->dropIndex('al_record_id_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notif_user_read_idx');
        });
    }
};
