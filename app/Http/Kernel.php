<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // ... other middleware
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'is.student' => \App\Http\Middleware\IsStudent::class,
        'is.officer' => \App\Http\Middleware\IsOfficer::class,
        'is.registrar' => \App\Http\Middleware\IsRegistrar::class,
        'is.admin' => \App\Http\Middleware\IsAdmin::class,
    ];
}