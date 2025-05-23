<?php

use App\Http\Controllers\HrControllers\HrDeductionController;
use App\Http\Controllers\HrControllers\HrEmployeeController;
use App\Http\Controllers\HrControllers\HrPayrollController;
use Illuminate\Support\Facades\Route;





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
