<?php

use App\Http\Controllers\HrControllers\HrDeductionController;
use App\Http\Controllers\HrControllers\HrEmployeeController;
use App\Http\Controllers\HrControllers\HrPayrollController;
use Illuminate\Support\Facades\Route;



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
        Route::post('/updateProfile/{id}', 'updatePassportPhoto')->name('updateProfilePhoto');

    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:HR_OFFICER'])
->prefix('hr/payroll')
->controller(HrPayrollController::class)
->name('hr.payrolls.')
->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/pending', 'pending')->name('pending');
    Route::get('/approved', 'approved')->name('approved');
    Route::get('/rejected', 'rejected')->name('rejected');

    Route::post('/{payroll}/reject', 'reject')->name('reject');
    Route::post('/approve-all', 'approveAll')->name('approveAll');
});


Route::middleware(['auth', 'HasCompanyProfile', 'role:HR_OFFICER'])
    ->prefix('/hr/employees/{employee}/deductions')
    ->controller(HrDeductionController::class)
    ->name('hr.deductions.')
    ->group(function () {
        Route::get('/', 'index')->name('index');                     // List deductions for employee
        Route::get('/create', 'create')->name('create');             // Show form to create a deduction for employee
        Route::post('/', 'store')->name('store');                    // Store new deduction for employee
        Route::get('/{deduction}', 'show')->name('show');            // Show a single deduction
        Route::get('/{deduction}/edit', 'edit')->name('edit');       // Edit a deduction
        Route::put('/{deduction}', 'update')->name('update');        // Update deduction
        Route::delete('/{deduction}', 'destroy')->name('destroy');   // Delete deduction
    });
