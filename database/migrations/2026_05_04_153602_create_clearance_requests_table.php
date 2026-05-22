<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clearance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('reference_no')->unique();
            $table->enum('type', ['graduation', 'withdrawal', 'transfer', 'dismissal', 'temporary_leave', 'semester_completion']);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'approved', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->date('requested_date');
            $table->timestamp('completed_at')->nullable();
            $table->string('certificate_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clearance_requests');
    }
};