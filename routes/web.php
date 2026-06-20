<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\VerifyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Verification Routes
Route::get('/verify', [VerifyController::class, 'index'])->name('verify');
Route::post('/verify/check', [VerifyController::class, 'check'])->name('verify.check');
// QR / link verification via query string (avoids encoded slashes in the path,
// which Apache rejects). Must be declared before the catch-all {reference} route.
Route::get('/verify/lookup', [VerifyController::class, 'lookup'])->name('verify.lookup');
Route::get('/verify/{reference}', [VerifyController::class, 'show'])->where('reference', '.*');
Route::get('/api/verify/{reference}', [VerifyController::class, 'apiVerify'])->where('reference', '.*')->name('api.verify');

// Include authentication routes
require __DIR__.'/auth.php';

// Include role-based routes
require __DIR__.'/student.php';
require __DIR__.'/department.php';
require __DIR__.'/registrar.php';
require __DIR__.'/admin.php';

// Fallback route for 404
Route::fallback(function () {
    return view('errors.404');
});