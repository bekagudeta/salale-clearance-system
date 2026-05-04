<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Department\DashboardController;
use App\Http\Controllers\Department\ApprovalController;
use App\Http\Controllers\Department\HistoryController;

Route::middleware(['auth', 'role:department_officer'])->prefix('department')->name('department.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{id}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
});