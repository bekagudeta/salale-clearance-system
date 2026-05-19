<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert or update email settings
        $settings = [
            ['key' => 'mail_mailer', 'value' => 'smtp'],
            ['key' => 'mail_host', 'value' => 'smtp.gmail.com'],
            ['key' => 'mail_port', 'value' => '587'],
            ['key' => 'mail_username', 'value' => null],
            ['key' => 'mail_password', 'value' => null],
            ['key' => 'mail_encryption', 'value' => 'tls'],
            ['key' => 'mail_from_address', 'value' => 'noreply@example.com'],
            ['key' => 'mail_from_name', 'value' => 'Salale Clearance System'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->where('key', $setting['key'])->delete();
            DB::table('settings')->insert($setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the settings we just created
        DB::table('settings')->whereIn('key', [
            'mail_mailer',
            'mail_host', 
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
        ])->delete();
    }
};
