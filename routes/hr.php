<?php

use App\Http\Controllers\HrControllers\HrEmployeeController;
use App\Http\Controllers\HrControllers\HrLeavesController;
use App\Http\Controllers\HrControllers\HrLeaveTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'HasCompanyProfile', 'role:HR_OFFICER'])
    ->prefix('/hr/leave')
    ->controller(HrLeavesController::class)
    ->name('hr.leave.')
    ->group(function () {
        Route::get('/index', 'index')
            ->name('index');
        Route::get('/{leave}', 'show')->name('show');
        Route::post('/inspect/{leave}', 'inspect')->name('inspect');
    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:HR_OFFICER'])
    ->prefix('/hr/leave/type')
    ->controller(HrLeaveTypeController::class)
    ->name('hr.leave.type.')
    ->group(function () {
        Route::get('/index', 'index')
            ->name('index');
        Route::post('/store', 'store')
            ->name('store');
        Route::put('/update/{leaveType}', 'update')
            ->name('update');
        Route::delete('/destroy/{leaveType}', 'destroy')->name('destroy');
    });
    

    Route::middleware(['auth', 'HasCompanyProfile', 'role:HR_OFFICER'])
    ->prefix('/hr/employee')
    ->controller(HrEmployeeController::class)
    ->name('hr.employees.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('update/{id}', 'update')->name('update');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/updatePassword/{id}', 'updatePassword')
            ->name('update.password');
    });
