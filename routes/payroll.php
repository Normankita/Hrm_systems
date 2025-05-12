<?php
use App\Http\Controllers\PayrollControllers\PayrollEmployeeController;
use Illuminate\Support\Facades\Route;


    Route::middleware(['auth', 'HasCompanyProfile', 'role:PAYROLL_MANAGER'])
    ->prefix('/payroll/employee')
    ->controller(PayrollEmployeeController::class)
    ->name('payroll.employees.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
    });