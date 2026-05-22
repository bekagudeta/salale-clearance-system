<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EmailConfigService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repository bindings are provided by RepositoryServiceProvider to avoid duplication.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load email configuration from database at runtime
        try {
            EmailConfigService::loadFromDatabase();
        } catch (\Exception $e) {
            // Silently fail if database is not ready (e.g., during migration)
            \Log::debug('Could not load email config from database: ' . $e->getMessage());
        }
    }
}