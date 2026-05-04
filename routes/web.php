<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');

require __DIR__.'/auth.php';
require __DIR__.'/student.php';
require __DIR__.'/department.php';
require __DIR__.'/registrar.php';
require __DIR__.'/admin.php';