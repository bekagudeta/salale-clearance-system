<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('certificate_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clearance_id')->constrained('clearance_requests')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->string('action'); // download, verify, view, regenerate
            $table->string('security_code')->unique();
            $table->dateTime('issued_date')->nullable();
            $table->dateTime('validity_date')->nullable();
            $table->string('issued_by')->nullable();
            $table->dateTime('timestamp')->nullable();
            $table->timestamps();
            
            $table->index('clearance_id');
            $table->index('user_id');
            $table->index('security_code');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_audits');
    }
};
