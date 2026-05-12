<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            'is.admin' => \App\Http\Middleware\IsAdmin::class,
            'is.student' => \App\Http\Middleware\IsStudent::class,
            'is.officer' => \App\Http\Middleware\IsOfficer::class,
            'is.registrar' => \App\Http\Middleware\IsRegistrar::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withSingletons([
        \App\Repositories\Interfaces\ClearanceRepositoryInterface::class => \App\Repositories\ClearanceRepository::class,
        \App\Repositories\Interfaces\ReportRepositoryInterface::class => \App\Repositories\ReportRepository::class,
        \App\Repositories\Interfaces\UserRepositoryInterface::class => \App\Repositories\UserRepository::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
