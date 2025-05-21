<?php

use App\Http\Controllers\EmployeeControllers\EmployeeLeaveController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageEmployeeController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageLeavesController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageLeaveTypeController;
use App\Http\Controllers\EmployeeControllers\EmployeePayGradeController;
use App\Http\Controllers\EmployeeControllers\EmployeePayrollController;
use App\Http\Controllers\EmployeeControllers\EmployeeProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth','HasCompanyProfile', 'role:EMPLOYEE',
    'can:has-any-permission,delete-user,edit-user'
])
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

Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('/employee/manage/leave')
    ->controller(EmployeeManageLeavesController::class)
    ->name('employee.manage.leave.')
    ->group(function () {
        Route::get('/show/{leave}', 'show')->name('show');
        Route::get('/index', 'index')
            ->name('index');
        Route::post('/inspect/{leave}', 'inspect')->name('inspect');
    });

Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('/employee/manage/leave/type')
    ->controller(EmployeeManageLeaveTypeController::class)
    ->name('employee.manage.leave.type.')
    ->group(function () {
        Route::get('/index', 'index')
            ->name('index');
        Route::post('/store', 'store')
            ->name('store');
        Route::put('/update/{leaveType}', 'update')
            ->name('update');
        Route::delete('/destroy/{leaveType}', 'destroy')->name('destroy');
    });




// Employee PayGrade Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/paygrade')
    ->name('employee.manage.paygrades.')
    ->controller(EmployeePayGradeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{payGrade}', 'show')->name('show');
        Route::post('/store', 'store')->name('store');
        Route::patch('/update/{payGrade}', 'update')->name('update');
        Route::delete('/delete/{payGrade}', 'destroy')->name('delete');
        Route::get('/edit/{payGrade}', 'edit')->name('edit');
    });

Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/payrolls')
    ->name('employee.manage.payrolls.')
    ->controller(EmployeePayrollController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/generate-all', 'generateAll')->name('generateAll');
        Route::get('/show/{payroll}', 'show')->name('show');
    });



Route::middleware(['auth', 'HasCompanyProfile','role:EMPLOYEE'])
    ->prefix('/employee/manage/employee')
    ->controller(EmployeeManageEmployeeController::class)
    ->name('employee.manage.employees.')
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
