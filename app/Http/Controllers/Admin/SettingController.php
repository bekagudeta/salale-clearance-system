<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEmailConfigRequest;
use App\Models\Setting;
use App\Services\EmailConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'university_name' => 'nullable|string|max:255',
            'university_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'reminder_days' => 'nullable|integer|min:1|max:30',
            'auto_complete_days' => 'nullable|integer|min:1|max:90',
            'maintenance_mode' => 'nullable|boolean',
        ]);
        
        foreach ($request->except(['_token', '_method', 'university_logo']) as $key => $value) {
            Setting::set($key, $value);
        }
        
        // Handle logo upload
        if ($request->hasFile('university_logo')) {
            $path = $request->file('university_logo')->store('logos', 'public');
            Setting::set('university_logo', $path);
        }
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    public function emailSettings()
    {
        // Load settings from database
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        
        // Set default values if not configured
        if (empty($settings['mail_from_address'])) {
            $settings['mail_from_address'] = env('MAIL_FROM_ADDRESS', 'noreply@salale.edu.et');
        }
        if (empty($settings['mail_from_name'])) {
            $settings['mail_from_name'] = env('MAIL_FROM_NAME', 'Salale Clearance System');
        }
        if (empty($settings['mail_host'])) {
            $settings['mail_host'] = env('MAIL_HOST', 'smtp.gmail.com');
        }
        if (empty($settings['mail_port'])) {
            $settings['mail_port'] = env('MAIL_PORT', 587);
        }
        if (empty($settings['mail_encryption'])) {
            $settings['mail_encryption'] = env('MAIL_ENCRYPTION', 'tls');
        }
        if (empty($settings['mail_mailer'])) {
            $settings['mail_mailer'] = env('MAIL_MAILER', 'smtp');
        }
        
        return view('admin.settings.email', compact('settings'));
    }

    public function updateEmailSettings(UpdateEmailConfigRequest $request)
    {
        $emailService = new EmailConfigService();
        
        // Get all validated data
        $settings = $request->validated();
        $testConnection = $request->input('test_connection', false);
        
        try {
            // If connection test is requested, test it first
            if ($testConnection) {
                $connectionResult = $emailService->testConnection($settings);
                if (!$connectionResult['success']) {
                    return redirect()->route('admin.settings.email')
                        ->withInput()
                        ->with('error', $connectionResult['message']);
                }
            }

            // Save settings to database
            foreach ($settings as $key => $value) {
                if ($key !== 'test_connection') {
                    Setting::set($key, $value);
                }
            }

            // Update .env file
            $envUpdated = $emailService->updateEnvFile($settings);
            
            // Load configuration into application
            EmailConfigService::loadFromDatabase();
            
            // Clear config cache to apply new settings
            \Artisan::call('config:clear');

            Log::info('Email settings updated successfully by user: ' . auth()->user()->email);

            $successMessage = '✓ Email settings saved successfully.';
            if ($testConnection) {
                $successMessage .= ' Connection verified!';
            }

            return redirect()->route('admin.settings.email')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Error updating email settings: ' . $e->getMessage());
            
            return redirect()->route('admin.settings.email')
                ->withInput()
                ->with('error', 'Failed to update email settings: ' . $e->getMessage());
        }
    }

    public function backup()
    {
        $backups = $this->getBackupFiles();
        return view('admin.settings.backup', compact('backups'));
    }

    public function createBackup()
    {
        $databaseName = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        
        $backupPath = storage_path('app/backups');
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
        
        $filename = "backup_{$databaseName}_" . date('Y-m-d_His') . ".sql";
        $filepath = $backupPath . '/' . $filename;

        // Escape every argument so special characters can't break out of the shell
        // command, and pass the password via MYSQL_PWD instead of --password so it
        // does not appear in the server's process list.
        $command = sprintf(
            'mysqldump --user=%s %s > %s',
            escapeshellarg($username),
            escapeshellarg($databaseName),
            escapeshellarg($filepath)
        );

        putenv("MYSQL_PWD={$password}");
        exec($command . ' 2>&1', $output, $exitCode);
        putenv('MYSQL_PWD'); // clear the password from the process environment

        if ($exitCode !== 0) {
            Log::error('Database backup failed', ['exit_code' => $exitCode, 'output' => $output]);

            // Remove the partial/empty file a failed dump may have left behind.
            if (is_file($filepath)) {
                @unlink($filepath);
            }

            return redirect()->route('admin.settings.backup')
                ->with('error', 'Backup failed. Please check that mysqldump is available and try again.');
        }

        return redirect()->route('admin.settings.backup')
            ->with('success', "Backup created successfully: {$filename}");
    }

    public function downloadBackup($filename)
    {
        $filepath = storage_path("app/backups/{$filename}");
        
        if (!file_exists($filepath)) {
            return redirect()->back()->with('error', 'Backup file not found.');
        }
        
        return response()->download($filepath);
    }

    public function deleteBackup($filename)
    {
        $filepath = storage_path("app/backups/{$filename}");
        
        if (file_exists($filepath)) {
            unlink($filepath);
            return redirect()->back()->with('success', 'Backup deleted successfully.');
        }
        
        return redirect()->back()->with('error', 'Backup file not found.');
    }

    private function getBackupFiles()
    {
        $backupPath = storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            return [];
        }
        
        $files = glob($backupPath . '/*.sql');
        $backups = [];
        
        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => round(filesize($file) / 1024 / 1024, 2),
                'date' => date('Y-m-d H:i:s', filemtime($file)),
                'path' => $file,
            ];
        }
        
        return array_reverse($backups);
    }

    public function systemInfo()
    {
        $info = [
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'mysql_version' => \DB::select('select version() as version')[0]->version,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'server_os' => php_uname(),
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'loaded_extensions' => implode(', ', get_loaded_extensions()),
        ];
        
        return view('admin.settings.system-info', compact('info'));
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mail_mailer' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',
        ]);

        try {
            $emailService = new EmailConfigService();
            
            // Load current settings from database
            $dbSettings = Setting::all()->pluck('value', 'key')->toArray();
            
            // Use settings from form if provided, otherwise use saved settings from database
            $config = [
                'mail_mailer' => $request->input('mail_mailer') ?: ($dbSettings['mail_mailer'] ?? 'smtp'),
                'mail_host' => $request->input('mail_host') ?: ($dbSettings['mail_host'] ?? 'smtp.gmail.com'),
                'mail_port' => $request->input('mail_port') ?: ($dbSettings['mail_port'] ?? 587),
                'mail_username' => $request->input('mail_username') ?: ($dbSettings['mail_username'] ?? ''),
                'mail_password' => $request->input('mail_password') ?: ($dbSettings['mail_password'] ?? ''),
                'mail_encryption' => $request->input('mail_encryption') ?: ($dbSettings['mail_encryption'] ?? 'tls'),
                'mail_from_address' => $request->input('mail_from_address') ?: ($dbSettings['mail_from_address'] ?? env('MAIL_FROM_ADDRESS', 'noreply@salale.edu.et')),
                'mail_from_name' => $request->input('mail_from_name') ?: ($dbSettings['mail_from_name'] ?? 'Salale Clearance System'),
            ];

            // Validate configuration before sending
            $errors = $emailService->validate($config);
            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuration Error: ' . implode('. ', $errors)
                ], 422);
            }

            $result = $emailService->sendTestEmail($request->email, $config);
            
            return response()->json($result, $result['success'] ? 200 : 422);
        } catch (\Exception $e) {
            Log::error('Test email error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending the test email. Check server logs for details.'
            ], 500);
        }
    }

    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function backupSchedule(Request $request)
    {
        $request->validate([
            'auto_backup' => 'nullable|string',
            'retention_days' => 'nullable|integer|min:1|max:365',
        ]);

        try {
            // Save backup schedule settings
            foreach ($request->only(['auto_backup', 'retention_days']) as $key => $value) {
                Setting::set($key, $value);
            }

            return response()->json([
                'success' => true,
                'message' => 'Backup schedule updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function optimize(Request $request)
    {
        try {
            // Run Laravel optimization commands
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            \Artisan::call('optimize');

            return response()->json([
                'success' => true,
                'message' => 'Application optimized successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function maintenance(Request $request)
    {
        try {
            // Run maintenance tasks
            \Artisan::call('migrate', ['--force' => true]);
            \Artisan::call('cache:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Maintenance tasks completed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function exportInfo()
    {
        try {
            $info = [
                'laravel_version' => app()->version(),
                'php_version' => phpversion(),
                'mysql_version' => \DB::select('select version() as version')[0]->version,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'server_os' => php_uname(),
                'max_execution_time' => ini_get('max_execution_time'),
                'memory_limit' => ini_get('memory_limit'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'loaded_extensions' => implode(', ', get_loaded_extensions()),
                'exported_at' => now()->format('Y-m-d H:i:s'),
            ];

            $filename = 'system-info-' . date('Y-m-d_His') . '.json';
            $content = json_encode($info, JSON_PRETTY_PRINT);

            return response($content)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}