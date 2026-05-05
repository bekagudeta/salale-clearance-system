<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClearanceApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Public\VerifyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API Routes (no authentication required)
Route::prefix('v1')->group(function () {
    
    // Verification endpoints
    Route::get('/verify/{reference}', [VerifyController::class, 'apiVerify'])->name('api.verify');
    Route::get('/check-status/{reference}', [ClearanceApiController::class, 'checkStatus'])->name('api.check-status');
    
    // Public information
    Route::get('/departments', [ClearanceApiController::class, 'getDepartments'])->name('api.departments');
    Route::get('/clearance-types', [ClearanceApiController::class, 'getClearanceTypes'])->name('api.clearance-types');
});

// Protected API Routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Clearance API
    Route::prefix('clearances')->name('api.clearances.')->group(function () {
        Route::get('/', [ClearanceApiController::class, 'index'])->name('index');
        Route::get('/{id}', [ClearanceApiController::class, 'show'])->name('show');
        Route::post('/', [ClearanceApiController::class, 'store'])->name('store');
        Route::put('/{id}', [ClearanceApiController::class, 'update'])->name('update');
        Route::delete('/{id}', [ClearanceApiController::class, 'destroy'])->name('destroy');
        Route::get('/student/{studentId}', [ClearanceApiController::class, 'getByStudent'])->name('by-student');
        Route::get('/status/{status}', [ClearanceApiController::class, 'getByStatus'])->name('by-status');
    });
    
    // Approvals API
    Route::prefix('approvals')->name('api.approvals.')->group(function () {
        Route::get('/pending', [ClearanceApiController::class, 'getPendingApprovals'])->name('pending');
        Route::post('/{id}/approve', [ClearanceApiController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [ClearanceApiController::class, 'reject'])->name('reject');
    });
    
    // User API (Admin only)
    Route::prefix('users')->middleware('role:super_admin')->name('api.users.')->group(function () {
        Route::get('/', [UserApiController::class, 'index'])->name('index');
        Route::get('/{id}', [UserApiController::class, 'show'])->name('show');
        Route::post('/', [UserApiController::class, 'store'])->name('store');
        Route::put('/{id}', [UserApiController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserApiController::class, 'destroy'])->name('destroy');
        Route::get('/role/{role}', [UserApiController::class, 'getByRole'])->name('by-role');
    });
    
    // Reports API (Registrar and Admin only)
    Route::prefix('reports')->middleware('role:registrar|super_admin')->name('api.reports.')->group(function () {
        Route::get('/dashboard', [ReportApiController::class, 'dashboardStats'])->name('dashboard');
        Route::get('/monthly-trends', [ReportApiController::class, 'monthlyTrends'])->name('monthly-trends');
        Route::get('/department-performance', [ReportApiController::class, 'departmentPerformance'])->name('department-performance');
        Route::get('/graduation-stats/{year}', [ReportApiController::class, 'graduationStats'])->name('graduation-stats');
        Route::get('/export/{type}', [ReportApiController::class, 'export'])->name('export');
    });
    
    // Statistics API
    Route::prefix('statistics')->name('api.statistics.')->group(function () {
        // Student statistics
        Route::get('/student/{studentId}', [ClearanceApiController::class, 'studentStats'])->name('student');
        
        // Department statistics
        Route::get('/department/{departmentId}', [ClearanceApiController::class, 'departmentStats'])->name('department');
        
        // Registrar statistics
        Route::get('/registrar', [ClearanceApiController::class, 'registrarStats'])->name('registrar')
            ->middleware('role:registrar|super_admin');
        
        // Admin statistics
        Route::get('/admin', [ClearanceApiController::class, 'adminStats'])->name('admin')
            ->middleware('role:super_admin');
    });
    
    // Notifications API
    Route::prefix('notifications')->name('api.notifications.')->group(function () {
        Route::get('/', [UserApiController::class, 'getNotifications'])->name('index');
        Route::post('/{id}/read', [UserApiController::class, 'markNotificationRead'])->name('read');
        Route::post('/read-all', [UserApiController::class, 'markAllNotificationsRead'])->name('read-all');
        Route::delete('/{id}', [UserApiController::class, 'deleteNotification'])->name('delete');
    });
    
    // Profile API
    Route::prefix('profile')->name('api.profile.')->group(function () {
        Route::get('/', [UserApiController::class, 'getProfile'])->name('get');
        Route::put('/', [UserApiController::class, 'updateProfile'])->name('update');
        Route::post('/change-password', [UserApiController::class, 'changePassword'])->name('change-password');
    });
});