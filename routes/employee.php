<?php

use App\Http\Controllers\EmployeeControllers\EmployeeLeaveController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageEmployeeController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageLeavesController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageLeaveTypeController;
use App\Http\Controllers\EmployeeControllers\EmployeePayGradeController;
use App\Http\Controllers\EmployeeControllers\EmployeePayrollController;
use App\Http\Controllers\EmployeeControllers\EmployeeProfileController;
use Illuminate\Support\Facades\Route;

// Leave Request Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('/employee/leave')
    ->controller(EmployeeLeaveController::class)
    ->name('employees.leave.')
    ->group(function () {
        Route::get('/status', 'index')->name('status')->middleware(['can:view-leave']);
        Route::get('/request', 'create')->name('request')->middleware(['can:request-leave']);
        Route::post('/create', 'store')->name('store')->middleware(['can:request-leave']);
        Route::get('/{leave}', 'show')->name('show')->middleware(['can:view-leave']);
        Route::get('/{leave}/edit', 'edit')->name('edit')->middleware(['can:edit-leave']);
        Route::put('/{leave}', 'update')->name('update')->middleware(['can:edit-leave']);
        Route::delete('/{leave}', 'destroy')->name('destroy')->middleware(['can:delete-leave']);
    });

// Profile Routes
Route::middleware(['auth', 'HasCompanyProfile', 'HasDefaultConfigs', 'role:EMPLOYEE'])
    ->prefix('/employee/profile')
    ->controller(EmployeeProfileController::class)
    ->name('employees.profile.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{employee}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{employee}', 'update')->name('update');
    });

Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('/employee/profile')
    ->controller(EmployeeProfileController::class)
    ->name('employees.profile.')
    ->group(function () {
        Route::get('/{employee}/edit-password', 'editPassword')->name('edit_password');
        Route::put('/{employee}/update-password', 'updatePassword')->name('update_password');
        Route::post('/updateProfile/{id}', 'updatePassportPhoto')->name('updateProfilePhoto');
    });

// Manage Leave Response
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('/employee/manage/leave')
    ->controller(EmployeeManageLeavesController::class)
    ->name('employee.manage.leave.')
    ->group(function () {
        Route::get('/show/{leave}', 'show')->name('show')->middleware(['can:view-all-leave-requests']);
        Route::get('/index', 'index')->name('index')->middleware(['can:view-all-leave-requests']);
        Route::post('/inspect/{leave}', 'inspect')->name('inspect')->middleware(['can:respond-any-leave-request']);
    });

// Leave Types
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('/employee/manage/leave/type')
    ->controller(EmployeeManageLeaveTypeController::class)
    ->name('employee.manage.leave.type.')
    ->group(function () {
        Route::get('/index', 'index')->name('index')->middleware(['can:view-all-leaveTypes']);
        Route::post('/store', 'store')->name('store')->middleware(['can:create-any-leaveType']);
        Route::put('/update/{leaveType}', 'update')->name('update')->middleware(['can:edit-any-leaveType']);
        Route::delete('/destroy/{leaveType}', 'destroy')->name('destroy')->middleware(['can:delete-any-leaveType']);
    });

// PayGrade Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/paygrade')
    ->name('employee.manage.paygrades.')
    ->controller(EmployeePayGradeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware(['can:view-all-paygrade']);
        Route::get('/show/{payGrade}', 'show')->name('show')->middleware(['can:view-all-paygrade']);
        Route::post('/store', 'store')->name('store')->middleware(['can:create-any-paygrade']);
        Route::patch('/update/{payGrade}', 'update')->name('update')->middleware(['can:edit-any-paygrade']);
        Route::delete('/delete/{payGrade}', 'destroy')->name('delete')->middleware(['can:delete-any-paygrade']);
        Route::get('/edit/{payGrade}', 'edit')->name('edit')->middleware(['can:edit-any-paygrade']);
    });

// Payroll Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/payrolls')
    ->name('employee.manage.payrolls.')
    ->controller(EmployeePayrollController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware(['can:view-all-payroll']);
        Route::post('/generate-all', 'generateAll')->name('generateAll')->middleware(['can:create-any-payroll']);
        Route::get('/{payroll}/edit', 'edit')->name('edit')->middleware(['can:edit-any-payroll']);
        Route::put('/{payroll}', 'update')->name('update')->middleware(['can:edit-any-payroll']);
        Route::delete('/{payroll}', 'destroy')->name('destroy')->middleware(['can:delete-any-payroll']);
    });

// (Optional) Employee Management Routes if you plan to use them
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/employees')
    ->name('employee.manage.employees.')
    ->controller(EmployeeManageEmployeeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware(['can:view-all-employees']);
        Route::get('/create', 'create')->name('create')->middleware(['can:create-any-employee']);
        Route::post('/store', 'store')->name('store')->middleware(['can:create-any-employee']);
        Route::get('/{employee}/edit', 'edit')->name('edit')->middleware(['can:edit-any-employee']);
        Route::put('/{employee}', 'update')->name('update')->middleware(['can:edit-any-employee']);
        Route::delete('/{employee}', 'destroy')->name('destroy')->middleware(['can:delete-any-employee']);
    });
