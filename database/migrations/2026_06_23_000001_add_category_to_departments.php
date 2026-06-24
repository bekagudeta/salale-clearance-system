<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            // 'academic' departments act as the first-stage head/coordinator gate;
            // 'service' departments (clinic, housing, store, ...) open only after
            // the student's academic head approves.
            $table->string('category')->default('service')->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
