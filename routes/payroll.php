<?php

use App\Http\Controllers\EmployeeControllers\EmployeeProfileController;
use App\Http\Controllers\PayrollControllers\PayrollController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayrollControllers\PayrollEmployeeController;
use App\Http\Controllers\PayrollControllers\PayrollPayGradeController;

// Payroll Employee Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:PAYROLL_MANAGER'])
    ->prefix('payroll/employee')
    ->name('payroll.employees.')
    ->controller(PayrollEmployeeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::patch('/UpdatePayGrade/{employee}', 'UpdatePayGrade')->name('UpdatePayGrade');
    });

   Route::get('/employees/{employee}/payrolls/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');



// Payroll PayGrade Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:PAYROLL_MANAGER'])
    ->prefix('payroll/paygrade')
    ->name('payroll.paygrades.')
    ->controller(PayrollPayGradeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{payGrade}', 'show')->name('show');
        Route::post('/store', 'store')->name('store');
        Route::patch('/update/{payGrade}', 'update')->name('update');
        Route::delete('/delete/{payGrade}', 'destroy')->name('delete');
        Route::get('/edit/{payGrade}', 'edit')->name('edit');
    });

Route::middleware(['auth', 'HasCompanyProfile', 'role:PAYROLL_MANAGER'])
    ->prefix('payrolls')
    ->name('payrolls.')
    ->controller(PayrollController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/generate-all', 'generateAll')->name('generateAll');
        Route::get('/show/{payroll}', 'show')->name('show');

    });

