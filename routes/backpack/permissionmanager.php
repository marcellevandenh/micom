<?php

use App\Http\Controllers\Admin\UserCrudController;
use Backpack\PermissionManager\app\Http\Controllers\PermissionCrudController;
use Backpack\PermissionManager\app\Http\Controllers\RoleCrudController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', backpack_middleware()],
], function () {
    Route::crud('permission', PermissionCrudController::class);
    Route::crud('role', RoleCrudController::class);
    Route::crud('user', UserCrudController::class);
});
