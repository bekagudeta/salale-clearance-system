<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\ClearanceWebhookController;

/*
|--------------------------------------------------------------------------
| Webhook Routes
|--------------------------------------------------------------------------
| These routes are for external services to notify the system
*/

Route::prefix('webhook')->name('webhook.')->group(function () {
    
    // SMS delivery status webhook
    Route::post('/sms-status', [ClearanceWebhookController::class, 'smsStatus'])->name('sms-status');
    
    // Email delivery webhook
    Route::post('/email-status', [ClearanceWebhookController::class, 'emailStatus'])->name('email-status');
    
    // External system integration
    Route::post('/external-callback', [ClearanceWebhookController::class, 'externalCallback'])->name('external-callback');
});