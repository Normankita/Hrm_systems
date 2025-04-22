<?php

use App\Http\Controllers\AdminControllers\AdminEmployeeController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:ADMIN'])
    ->prefix('/admin/employee')
    ->controller(AdminEmployeeController::class)
    ->name('admin.employees.')
    ->group( function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{$id}', 'show')->name('show');
    });
