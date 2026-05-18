<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Registrar\DashboardController;
use App\Http\Controllers\Registrar\ClearanceController;
use App\Http\Controllers\Registrar\ReportController;
use App\Http\Controllers\Registrar\CertificateController;
use App\Http\Controllers\Registrar\SettingsController;

/*
|--------------------------------------------------------------------------
| Registrar Routes
|--------------------------------------------------------------------------
| All routes in this group require authentication and registrar role
*/

Route::middleware(['auth', 'is.registrar'])->prefix('registrar')->name('registrar.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Clearance Management
    Route::prefix('clearance')->name('clearance.')->group(function () {
        Route::get('/', [ClearanceController::class, 'index'])->name('index');
        Route::get('/{id}', [ClearanceController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [ClearanceController::class, 'approve'])->name('approve');
        Route::post('/{id}/finalize', [ClearanceController::class, 'finalize'])->name('finalize');
        Route::post('/{id}/reopen', [ClearanceController::class, 'reopen'])->name('reopen');
        Route::get('/{id}/print', [ClearanceController::class, 'print'])->name('print');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::post('/generate', [ReportController::class, 'generate'])->name('generate');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
        Route::get('/download/{filename}', [ReportController::class, 'download'])->name('download');
        
        // Specific Reports
        Route::get('/cleared-students', [ReportController::class, 'clearedStudents'])->name('cleared-students');
        Route::get('/rejected-requests', [ReportController::class, 'rejectedRequests'])->name('rejected-requests');
        Route::get('/department-delays', [ReportController::class, 'departmentDelays'])->name('department-delays');
        Route::get('/graduation-stats', [ReportController::class, 'graduationStats'])->name('graduation-stats');
        Route::get('/faculty-report/{faculty}', [ReportController::class, 'facultyReport'])->name('faculty-report');
    });
    
    // Certificate Management
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [CertificateController::class, 'index'])->name('index');
        Route::post('/{id}/regenerate', [CertificateController::class, 'regenerate'])->name('regenerate');
        Route::get('/{id}/download', [CertificateController::class, 'download'])->name('download');
        Route::post('/bulk-generate', [CertificateController::class, 'bulkGenerate'])->name('bulk-generate');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [App\Http\Controllers\Registrar\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [App\Http\Controllers\Registrar\ProfileController::class, 'update'])->name('update');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'edit'])->name('edit');
        Route::put('/', [SettingsController::class, 'update'])->name('update');
    });
    
    // Verification
    Route::get('/verify/{reference}', [CertificateController::class, 'verify'])->where('reference', '.*')->name('verify');
});