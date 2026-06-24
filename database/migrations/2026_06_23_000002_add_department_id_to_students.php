<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Direct link to the student's academic department (the head/coordinator
            // gate). Kept nullable + nullOnDelete so an unmapped student or a deleted
            // department never breaks the row; the legacy `department` string stays
            // for display/back-compat.
            $table->foreignId('department_id')
                ->nullable()
                ->after('department')
                ->constrained('departments')
                ->nullOnDelete();
        });

        $this->backfill();
    }

    /**
     * Best-effort link existing students to an academic department by matching
     * their free-text `department` against academic department names/slugs.
     */
    private function backfill(): void
    {
        $academic = DB::table('departments')
            ->where('category', 'academic')
            ->get(['id', 'name', 'slug']);

        if ($academic->isEmpty()) {
            return;
        }

        $byKey = [];
        foreach ($academic as $dept) {
            $byKey[$this->normalize($dept->name)] = $dept->id;
            $byKey[$this->normalize($dept->slug)] = $dept->id;
        }

        DB::table('students')
            ->whereNull('department_id')
            ->orderBy('id')
            ->select('id', 'department')
            ->chunkById(200, function ($students) use ($byKey) {
                foreach ($students as $student) {
                    $key = $this->normalize($student->department);
                    if ($key !== '' && isset($byKey[$key])) {
                        DB::table('students')
                            ->where('id', $student->id)
                            ->update(['department_id' => $byKey[$key]]);
                    }
                }
            });
    }

    private function normalize(?string $value): string
    {
        return preg_replace('/[^a-z0-9]/', '', strtolower((string) $value));
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
