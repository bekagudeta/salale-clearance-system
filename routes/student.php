<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ClearanceController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\NotificationController;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
| All routes in this group require authentication and student role
*/

Route::middleware(['auth', 'is.student'])->prefix('student')->name('student.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Clearance Management
    Route::prefix('clearance')->name('clearance.')->group(function () {
        Route::get('/create', [ClearanceController::class, 'create'])->name('create');
        Route::post('/', [ClearanceController::class, 'store'])->name('store');
        Route::get('/history', [ClearanceController::class, 'history'])->name('history');
        Route::get('/{id}', [ClearanceController::class, 'show'])->name('show')->whereNumber('id');
        Route::post('/{id}/cancel', [ClearanceController::class, 'cancel'])->name('cancel')->whereNumber('id');
    });
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    });
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });
});