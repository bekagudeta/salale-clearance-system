<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update email settings with correct email
        $settings = [
            ['key' => 'mail_username', 'value' => 'noreply@example.com'],
            ['key' => 'mail_from_address', 'value' => 'noreply@example.com'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->where('key', $setting['key'])->update(['value' => $setting['value']]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous email
        DB::table('settings')->where('key', 'mail_username')->update(['value' => 'noreply@example.com']);
        DB::table('settings')->where('key', 'mail_from_address')->update(['value' => 'noreply@example.com']);
    }
};
