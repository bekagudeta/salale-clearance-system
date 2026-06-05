<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->removeDuplicateApprovals();

        Schema::table('clearance_approvals', function (Blueprint $table) {
            $table->unique(
                ['clearance_request_id', 'department_id'],
                'clearance_approvals_request_department_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('clearance_approvals', function (Blueprint $table) {
            $table->dropUnique('clearance_approvals_request_department_unique');
        });
    }

    private function removeDuplicateApprovals(): void
    {
        $duplicateGroups = DB::table('clearance_approvals')
            ->select('clearance_request_id', 'department_id')
            ->groupBy('clearance_request_id', 'department_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicateGroups as $group) {
            $approvals = DB::table('clearance_approvals')
                ->where('clearance_request_id', $group->clearance_request_id)
                ->where('department_id', $group->department_id)
                ->get();

            $keepId = $approvals
                ->sortByDesc(fn ($approval) => $this->approvalPriority($approval))
                ->first()
                ->id;

            DB::table('clearance_approvals')
                ->where('clearance_request_id', $group->clearance_request_id)
                ->where('department_id', $group->department_id)
                ->where('id', '!=', $keepId)
                ->delete();
        }
    }

    private function approvalPriority(object $approval): array
    {
        return [
            $approval->status === 'pending' ? 0 : 1,
            $approval->approved_at ?? $approval->updated_at ?? $approval->created_at,
            $approval->id,
        ];
    }
};
