<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

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
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.email', compact('settings'));
    }

    public function updateEmailSettings(Request $request)
    {
        $request->validate([
            'mail_mailer' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',
        ]);
        
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            Setting::set($key, $value);
            
            // Update .env file (optional - for production)
            $this->updateEnvFile($key, $value);
        }
        
        return redirect()->route('admin.settings.email')
            ->with('success', 'Email settings updated successfully.');
    }

    private function updateEnvFile($key, $value)
    {
        $key = strtoupper($key);
        $path = base_path('.env');
        
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $content = preg_replace("/{$key}=.*/", "{$key}={$value}", $content);
            file_put_contents($path, $content);
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
        
        $command = "mysqldump --user={$username} --password={$password} {$databaseName} > {$filepath}";
        exec($command);
        
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
        ]);

        try {
            $email = $request->email;
            
            // Send test email
            \Mail::raw('This is a test email from Salale University Clearance System.', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email - Clearance System')
                    ->from(config('mail.from.address', config('mail.from.name')));
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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