<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Department\DashboardController;
use App\Http\Controllers\Department\ApprovalController;
use App\Http\Controllers\Department\HistoryController;
use App\Http\Controllers\Department\StudentCaseController;

/*
|--------------------------------------------------------------------------
| Department Officer Routes
|--------------------------------------------------------------------------
| All routes in this group require authentication and department officer role
*/

Route::middleware(['auth', 'is.officer'])->prefix('department')->name('department.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Approval Management
    Route::prefix('approvals')->name('approvals.')->group(function () {
        Route::get('/', [ApprovalController::class, 'index'])->name('index');
        Route::post('/{id}/approve', [ApprovalController::class, 'approve'])->name('approve');
        Route::post('/{id}/flag-case', [ApprovalController::class, 'flagCase'])->name('flag-case');
        Route::post('/{id}/reject', [ApprovalController::class, 'reject'])->name('reject');
        Route::post('/bulk-approve', [ApprovalController::class, 'bulkApprove'])->name('bulk-approve');
    });

    // Student case records (recorded before clearance requests)
    Route::prefix('cases')->name('cases.')->group(function () {
        Route::get('/', [StudentCaseController::class, 'index'])->name('index');
        Route::get('/create', [StudentCaseController::class, 'create'])->name('create');
        Route::get('/lookup', [StudentCaseController::class, 'lookup'])->name('lookup');
        Route::post('/', [StudentCaseController::class, 'store'])->name('store');
        Route::post('/{id}/clear', [StudentCaseController::class, 'clear'])->name('clear');
    });
    
    // History
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/{id}', [HistoryController::class, 'show'])->name('history.show');
    
    // Statistics
    Route::get('/stats', [DashboardController::class, 'statistics'])->name('statistics');
});