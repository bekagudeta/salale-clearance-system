<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('students')) {
            DB::statement("ALTER TABLE `students` MODIFY `gender` ENUM('male','female','other') NULL");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('students')) {
            DB::statement("ALTER TABLE `students` MODIFY `gender` ENUM('male','female') NULL");
        }
    }
};
