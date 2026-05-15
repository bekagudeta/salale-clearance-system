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
        $this->app->bind(
            \App\Repositories\Interfaces\ClearanceRepositoryInterface::class,
            \App\Repositories\ClearanceRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Interfaces\ReportRepositoryInterface::class,
            \App\Repositories\ReportRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
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