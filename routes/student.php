<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ClearanceController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\NotificationController;

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/clearance/create', [ClearanceController::class, 'create'])->name('clearance.create');
    Route::post('/clearance', [ClearanceController::class, 'store'])->name('clearance.store');
    Route::get('/clearance/{id}', [ClearanceController::class, 'show'])->name('clearance.show');
    Route::get('/clearance-history', [ClearanceController::class, 'history'])->name('clearance.history');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});