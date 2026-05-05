<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LogController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| All routes in this group require authentication and super admin role
*/

Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::delete('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/{id}/impersonate', [UserController::class, 'impersonate'])->name('impersonate');
        Route::post('/stop-impersonation', [UserController::class, 'stopImpersonation'])->name('stop-impersonation');
        Route::post('/export', [UserController::class, 'export'])->name('export');
    });
    
    // Role Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
        
        // Permissions
        Route::get('/permissions', [RoleController::class, 'permissions'])->name('permissions');
        Route::post('/permissions', [RoleController::class, 'createPermission'])->name('permissions.store');
        Route::delete('/permissions/{id}', [RoleController::class, 'deletePermission'])->name('permissions.destroy');
        Route::post('/{id}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('assign-permissions');
    });
    
    // Department Management
    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::get('/create', [DepartmentController::class, 'create'])->name('create');
        Route::post('/', [DepartmentController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [DepartmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DepartmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [DepartmentController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [DepartmentController::class, 'reorder'])->name('reorder');
        Route::post('/{id}/toggle-status', [DepartmentController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/assign-officer', [DepartmentController::class, 'assignOfficer'])->name('assign-officer');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'update'])->name('update');
        Route::get('/email', [SettingController::class, 'emailSettings'])->name('email');
        Route::post('/email', [SettingController::class, 'updateEmailSettings'])->name('email.update');
        Route::get('/backup', [SettingController::class, 'backup'])->name('backup');
        Route::post('/backup/create', [SettingController::class, 'createBackup'])->name('backup.create');
        Route::get('/backup/download/{filename}', [SettingController::class, 'downloadBackup'])->name('backup.download');
        Route::delete('/backup/delete/{filename}', [SettingController::class, 'deleteBackup'])->name('backup.delete');
        Route::get('/system-info', [SettingController::class, 'systemInfo'])->name('system-info');
        Route::post('/clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
    });
    
    // Activity Logs
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [LogController::class, 'index'])->name('index');
        Route::get('/{id}', [LogController::class, 'show'])->name('show');
        Route::delete('/clear', [LogController::class, 'clear'])->name('clear');
        Route::get('/export', [LogController::class, 'export'])->name('export');
    });
    
    // System Health
    Route::get('/system-health', [DashboardController::class, 'systemHealth'])->name('system-health');
    Route::get('/backup', [DashboardController::class, 'backup'])->name('backup');
});