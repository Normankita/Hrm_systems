<?php

use App\Http\Controllers\EmployeeControllers\EmployeeDeductionController;
use App\Http\Controllers\EmployeeControllers\EmployeeLeaveController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageEmployeeController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageLeavesController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageLeaveTypeController;
use App\Http\Controllers\EmployeeControllers\EmployeeManagePayrollController;
use App\Http\Controllers\EmployeeControllers\EmployeeManagePayrollEmployeeController;
use App\Http\Controllers\EmployeeControllers\EmployeeManagePayrollPayGradeController;
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
    ->prefix('employee/profile')
    ->controller(EmployeeProfileController::class)
    ->name('employees.profile.')
    ->group(function () {
        Route::get('/{employee}/edit-password', 'editPassword')->name('edit_password');
        Route::put('/{employee}/update-password', 'updatePassword')->name('update_password');
        Route::post('/updateProfile/{id}', 'updatePassportPhoto')->name('updateProfilePhoto');
    });

// Manage Leave Response
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/leave')
    ->controller(EmployeeManageLeavesController::class)
    ->name('employee.manage.leave.')
    ->group(function () {
        Route::get('/show/{leave}', 'show')->name('show')->middleware(['can:view_leave-requests']);
        Route::get('/index', 'index')->name('index')->middleware(['can:view_leave-requests']);
        Route::post('/inspect/{leave}', 'inspect')->name('inspect')->middleware(['can:respond_leave-request']);
    });

// Leave Types
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/leave/type')
    ->controller(EmployeeManageLeaveTypeController::class)
    ->name('employee.manage.leave.type.')
    ->group(function () {
        Route::get('/index', 'index')->name('index')->middleware(['can:view_leaveTypes']);
        Route::post('/store', 'store')->name('store')->middleware(['can:create_leaveType']);
        Route::put('/update/{leaveType}', 'update')->name('update')->middleware(['can:edit_leaveType']);
        Route::delete('/destroy/{leaveType}', 'destroy')->name('destroy')->middleware(['can:delete_leaveType']);
    });

// PayGrade Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/paygrade')
    ->name('employee.manage.paygrades.')
    ->controller(EmployeePayGradeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware(['can:view_paygrade']);
        Route::get('/show/{payGrade}', 'show')->name('show')->middleware(['can:view_paygrade']);
        Route::post('/store', 'store')->name('store')->middleware(['can:create_paygrade']);
        Route::patch('/update/{payGrade}', 'update')->name('update')->middleware(['can:edit_paygrade']);
        Route::delete('/delete/{payGrade}', 'destroy')->name('delete')->middleware(['can:delete_paygrade']);
        Route::get('/edit/{payGrade}', 'edit')->name('edit')->middleware(['can:edit_paygrade']);
    });

// Payroll Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/payrolls')
    ->name('employee.manage.payrolls.')
    ->controller(EmployeePayrollController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware(['can:view_payroll']);
        Route::post('/generate-all', 'generateAll')->name('generateAll')->middleware(['can:create_payroll']);
        Route::get('/{payroll}/edit', 'edit')->name('edit')->middleware(['can:edit_payroll']);
        Route::put('/{payroll}', 'update')->name('update')->middleware(['can:edit_payroll']);
        Route::delete('/{payroll}', 'destroy')->name('destroy')->middleware(['can:delete_payroll']);
    });

// (Optional) Employee Management Routes if you plan to use them
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('/employee/manage/employees')
    ->name('employee.manage.employees.')
    ->controller(EmployeeManageEmployeeController::class)
    ->group(function () {
        Route::get('/create', 'create')->name('create')->middleware(['can:create_employees']);
        Route::get('/', 'index')->name('index')->middleware(['can:view_employees']);
        Route::get('/{employee}', 'show')->name('show')->middleware(['can:view_employees']);
        Route::post('/store', 'store')->name('store')->middleware(['can:create_employees']);
        Route::get('/{employee}/edit', 'edit')->name('edit')->middleware(['can:edit_employees']);
        Route::put('/{employee}', 'update')->name('update')->middleware(['can:edit_employees']);
        Route::delete('/{employee}', 'destroy')->name('destroy')->middleware(['can:delete_employees']);
        Route::post('/updatePassword/{id}', 'updatePassword')
            ->name('update.password');
        Route::post('/updateProfile/{id}', 'updatePassportPhoto')->name('updateProfilePhoto');
    });


    Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/{employee}/deductions')
    ->controller(EmployeeDeductionController::class)
    ->name('employee.manage.deductions.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware(['can:view_deductions']);                     // List deductions for employee
        Route::get('/create', 'create')->name('create')->middleware(['can:create_deductions']);             // Show form to create a deduction for employee
        Route::post('/', 'store')->name('store')->middleware(['can:create_deductions']);                    // Store new deduction for employee
        Route::get('/{deduction}', 'show')->name('show')->middleware(['can:view_deductions']);            // Show a single deduction
        Route::get('/{deduction}/edit', 'edit')->name('edit')->middleware(['can:edit_deductions']);       // Edit a deduction
        Route::put('/{deduction}', 'update')->name('update')->middleware(['can:edit_deductions']);        // Update deduction
        Route::delete('/{deduction}', 'destroy')->name('destroy')->middleware(['can:delete_deductions']);   // Delete deduction
    });

    // Payroll Routes

    // Payroll Employee Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/payroll/employee')
    ->name('employee.manage.payroll.employees.')
    ->controller(EmployeeManagePayrollEmployeeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware(['can:view_employees']);
        Route::get('/show/{id}', 'show')->name('show')->middleware(['can:view_employee']);
        Route::patch('/UpdatePayGrade/{employee}', 'UpdatePayGrade')->name('UpdatePayGrade')->middleware(['can:edit_employees']);
    });

   Route::get('/employees/{employee}/payrolls/{payroll}', [EmployeeManagePayrollController::class, 'show'])->name('payroll.show');
