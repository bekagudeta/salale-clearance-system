<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class EmailConfigService
{
    /**
     * Load mail configuration from Settings table
     */
    public static function loadFromDatabase(): void
    {
        try {
            // Use raw query to avoid circular dependency issues
            $settings = \DB::table('settings')
                ->whereIn('key', [
                    'mail_mailer', 'mail_host', 'mail_port', 'mail_username',
                    'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'
                ])
                ->pluck('value', 'key')
                ->toArray();

            if (empty($settings)) {
                return;
            }

            // Set mail configuration
            Config::set('mail.mailer_driver', $settings['mail_mailer'] ?? 'smtp');
            Config::set('mail.from_address', $settings['mail_from_address'] ?? env('MAIL_FROM_ADDRESS'));
            Config::set('mail.from_name', $settings['mail_from_name'] ?? env('MAIL_FROM_NAME'));
            
            Config::set('mail.smtp_host', $settings['mail_host'] ?? env('MAIL_HOST'));
            Config::set('mail.smtp_port', $settings['mail_port'] ?? env('MAIL_PORT'));
            Config::set('mail.smtp_encryption', $settings['mail_encryption'] ?? 'tls');
            Config::set('mail.smtp_username', $settings['mail_username'] ?? env('MAIL_USERNAME'));
            Config::set('mail.smtp_password', $settings['mail_password'] ?? env('MAIL_PASSWORD'));

            // Update the actual mailer config
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => $settings['mail_host'] ?? env('MAIL_HOST'),
                'port' => $settings['mail_port'] ?? 587,
                'encryption' => $settings['mail_encryption'] ?? 'tls',
                'username' => $settings['mail_username'] ?? env('MAIL_USERNAME'),
                'password' => $settings['mail_password'] ?? env('MAIL_PASSWORD'),
                'timeout' => 10,
            ]);

            Config::set('mail.default', $settings['mail_mailer'] ?? 'smtp');
            Config::set('mail.from', [
                'address' => $settings['mail_from_address'] ?? env('MAIL_FROM_ADDRESS'),
                'name' => $settings['mail_from_name'] ?? env('MAIL_FROM_NAME'),
            ]);

            Log::debug('Mail configuration loaded from database');
        } catch (\Exception $e) {
            Log::warning('Failed to load mail config from database: ' . $e->getMessage());
        }
    }

    /**
     * Test SMTP connection
     */
    public function testConnection(array $config): array
    {
        try {
            // Validate basic requirements
            $errors = $this->validate($config);
            if (!empty($errors)) {
                return [
                    'success' => false,
                    'message' => 'Configuration Error: ' . implode(', ', $errors)
                ];
            }

            // Try to establish SMTP connection
            $transport = $this->testSmtpConnection($config);

            if (!$transport) {
                return [
                    'success' => false,
                    'message' => 'Failed to connect to SMTP server. Please check your host and port settings.'
                ];
            }

            return [
                'success' => true,
                'message' => 'Connection successful! Your email configuration is working correctly.'
            ];
        } catch (\Exception $e) {
            Log::error('Email connection test failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $this->formatErrorMessage($e->getMessage())
            ];
        }
    }

    /**
     * Test SMTP connection specifically
     */
    private function testSmtpConnection(array $config): bool
    {
        try {
            $host = $config['mail_host'];
            $port = $config['mail_port'];
            $timeout = 5;

            $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
            
            if (!$connection) {
                Log::warning("SMTP Connection failed to {$host}:{$port} - Error: {$errstr}");
                return false;
            }

            fclose($connection);
            return true;
        } catch (\Exception $e) {
            Log::error('SMTP connection test error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send test email
     */
    public function sendTestEmail(string $email, array $config): array
    {
        try {
            // Validate email address
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return [
                    'success' => false,
                    'message' => 'The test email address is invalid.'
                ];
            }

            // Validate from address
            if (!filter_var($config['mail_from_address'], FILTER_VALIDATE_EMAIL)) {
                return [
                    'success' => false,
                    'message' => 'The "From Address" is invalid. Please use a valid email address.'
                ];
            }

            // Set mail configuration temporarily
            $this->setMailConfig($config);

            // Send test email
            Mail::raw(
                "This is a test email from Salale University Clearance System.\n\n" .
                "If you received this message, your email configuration is working correctly!\n\n" .
                "Date: " . now()->format('Y-m-d H:i:s'),
                function ($message) use ($email, $config) {
                    $message->to($email)
                        ->subject('✓ Test Email - Clearance System Configuration')
                        ->from($config['mail_from_address'], $config['mail_from_name'] ?? 'Clearance System');
                }
            );

            Log::info("Test email sent successfully from {$config['mail_from_address']} to {$email}");
            return [
                'success' => true,
                'message' => "✓ Test email sent successfully to {$email}. Check your inbox!"
            ];
        } catch (\Exception $e) {
            Log::error('Test email failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $this->formatErrorMessage($e->getMessage())
            ];
        }
    }

    /**
     * Update environment file safely
     */
    public function updateEnvFile(array $settings): bool
    {
        try {
            $envPath = base_path('.env');

            if (!file_exists($envPath)) {
                Log::warning('.env file not found');
                return false;
            }

            if (!is_writable($envPath)) {
                Log::warning('.env file is not writable');
                return false;
            }

            $content = file_get_contents($envPath);

            // Map settings to env keys
            $mapping = [
                'mail_mailer' => 'MAIL_MAILER',
                'mail_host' => 'MAIL_HOST',
                'mail_port' => 'MAIL_PORT',
                'mail_username' => 'MAIL_USERNAME',
                'mail_password' => 'MAIL_PASSWORD',
                'mail_encryption' => 'MAIL_ENCRYPTION',
                'mail_from_address' => 'MAIL_FROM_ADDRESS',
                'mail_from_name' => 'MAIL_FROM_NAME',
            ];

            foreach ($mapping as $settingKey => $envKey) {
                if (isset($settings[$settingKey])) {
                    $value = $settings[$settingKey];
                    $value = is_null($value) ? '' : $value;

                    // Escape special characters for shell
                    if (strpos($value, ' ') !== false || strpos($value, '"') !== false) {
                        $value = '"' . str_replace('"', '\\"', $value) . '"';
                    }

                    // Replace or create new env variable
                    if (preg_match("/^{$envKey}=/m", $content)) {
                        $content = preg_replace(
                            "/^{$envKey}=.*/m",
                            "{$envKey}={$value}",
                            $content
                        );
                    } else {
                        $content .= "\n{$envKey}={$value}";
                    }
                }
            }

            file_put_contents($envPath, $content);
            Log::info('Email settings updated in .env file');
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update .env file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Set mail configuration dynamically
     */
    private function setMailConfig(array $config): void
    {
        Config::set('mail.mailer', $config['mail_mailer'] ?? 'smtp');
        Config::set('mail.from', [
            'address' => $config['mail_from_address'] ?? 'noreply@example.com',
            'name' => $config['mail_from_name'] ?? 'Application'
        ]);
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => $config['mail_host'],
            'port' => $config['mail_port'],
            'encryption' => $config['mail_encryption'] ?: null,
            'username' => $config['mail_username'],
            'password' => $config['mail_password'],
            'timeout' => 10,
        ]);
    }

    /**
     * Format error message for user
     */
    private function formatErrorMessage(string $message): string
    {
        // Remove sensitive information and provide user-friendly messages
        $message = strtolower($message);
        
        if (strpos($message, 'unauthorized') !== false || strpos($message, 'authentication') !== false) {
            return 'Authentication failed. Check your username and password. For Gmail, use an App Password if you have 2FA enabled.';
        } elseif (strpos($message, 'connection') !== false || strpos($message, 'cannot') !== false) {
            return 'Cannot connect to the mail server. Verify your host and port settings.';
        } elseif (strpos($message, 'timeout') !== false) {
            return 'Connection timeout. The server is not responding. Try increasing the timeout or check your network.';
        } elseif (strpos($message, 'certificate') !== false || strpos($message, 'ssl') !== false) {
            return 'SSL/TLS certificate error. Try changing encryption to TLS or None if using localhost.';
        }
        
        return 'Email configuration error. Please verify all settings and try again.';
    }

    /**
     * Validate email configuration
     */
    public function validate(array $config): array
    {
        $errors = [];

        if (empty($config['mail_host'])) {
            $errors['mail_host'] = 'SMTP host is required';
        }

        if (empty($config['mail_port']) || !is_numeric($config['mail_port'])) {
            $errors['mail_port'] = 'Valid port number is required';
        } elseif ($config['mail_port'] < 1 || $config['mail_port'] > 65535) {
            $errors['mail_port'] = 'Port must be between 1 and 65535';
        }

        if (empty($config['mail_from_address']) || !filter_var($config['mail_from_address'], FILTER_VALIDATE_EMAIL)) {
            $errors['mail_from_address'] = 'Valid "From Address" email is required';
        }

        if (empty($config['mail_from_name'])) {
            $errors['mail_from_name'] = '"From Name" is required';
        }

        return $errors;
    }
}
