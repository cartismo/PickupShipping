<?php

use Illuminate\Support\Facades\Route;
use Modules\PickupShipping\Http\Controllers\Admin\PickupShippingController;

/*
|--------------------------------------------------------------------------
| PickupShipping Admin Routes
|--------------------------------------------------------------------------
|
| Admin routes for pickup shipping settings.
|
*/

Route::prefix('modules/shipping/pickup-shipping')->name('admin.shipping.pickup.')->group(function () {
    Route::get('/settings', [PickupShippingController::class, 'index'])->name('settings');
    Route::put('/settings', [PickupShippingController::class, 'update'])->name('settings.update');
});