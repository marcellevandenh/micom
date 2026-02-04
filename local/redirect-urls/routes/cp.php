<?php

use Illuminate\Support\Facades\Route;
use Surgems\RedirectUrls\Controllers\DashboardController;
use Surgems\RedirectUrls\Controllers\ImportRedirectsController;
use Surgems\RedirectUrls\Controllers\RedirectController;

Route::group([
    'prefix' => '/redirect-urls',
    'as' => 'redirect-urls.',
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/import', [ImportRedirectsController::class, 'index'])->name('import');
    Route::post('/import', [ImportRedirectsController::class, 'store'])->name('import.submit');
    Route::get('/edit/{id}', [RedirectController::class, 'update'])->name('edit');
    Route::get('/delete/{id}', [RedirectController::class, 'delete'])->name('delete');
});
