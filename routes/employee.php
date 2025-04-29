<?php

use App\Http\Controllers\EmployeeControllers\EmployeeLeaveController;
use App\Http\Controllers\EmployeeControllers\EmployeeProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('/employee/leave')
    ->controller(EmployeeLeaveController::class)
    ->name('employees.leave.')
    ->group(function () {
        Route::get('/status', 'index')->name('status');
        Route::get('/request', 'create')->name('request');
        Route::post('/create', 'store')->name('store');
        Route::get('/{leave}', 'show')->name('show');
        Route::get('/{leave}/edit', 'edit')->name('edit');
        Route::put('/{leave}', 'update')->name('update');
        Route::delete('/{leave}', 'destroy')->name('destroy');
    });


    Route::middleware(['auth', 'HasCompanyProfile', 'HasDefaultConfigs' ,'role:EMPLOYEE'])
    ->prefix('/employee/profile')
    ->controller(EmployeeProfileController::class)
    ->name('employees.profile.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{employee}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('/{employee}/edit-password', 'editPassword')->name('edit_password');
        Route::put('/{employee}', 'update')->name('update');
        Route::put('/{employee}/update-password', 'updatePassword')->name('update_password');
    });
