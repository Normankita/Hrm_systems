<?php

use App\Http\Controllers\HrControllers\HrLeavesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'HasCompanyProfile', 'role:HR_OFFICER'])
    ->prefix('/hr/leave')
    ->controller(HrLeavesController::class)
    ->name('hr.leave.')
    ->group(function () {
        Route::get('/index', 'index')
            ->name('index');
        Route::get('/{leave}', 'show')->name('show');
    });
