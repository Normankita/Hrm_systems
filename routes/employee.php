<?php

use App\Http\Controllers\EmployeeControllers\EmployeeManageAllowanceFrequencyController;
use App\Http\Controllers\EmployeeControllers\EmployeeAllowanceGroupController;
use App\Http\Controllers\EmployeeControllers\EmployeeAttendancesController;
use App\Http\Controllers\EmployeeControllers\EmployeeLeaveController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageAllowancesController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageAttendanceController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageDeductionController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageDisbursements;
use App\Http\Controllers\EmployeeControllers\EmployeeManageEmployeeAllowancesController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageEmployeeController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageLeavesController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageLeaveTypeController;
use App\Http\Controllers\EmployeeControllers\EmployeeManageLoansController;
use App\Http\Controllers\EmployeeControllers\EmployeeManagePayrollController;
use App\Http\Controllers\EmployeeControllers\EmployeeManagePayrollEmployeeController;
use App\Http\Controllers\EmployeeControllers\EmployeePayrollController;
use App\Http\Controllers\EmployeeControllers\EmployeeProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeControllers\EmployeePayGradeController;

// Leave Request Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('/employee/leave')
    ->controller(EmployeeLeaveController::class)
    ->name('employees.leave.')
    ->group(function () {
        Route::get('/status', 'index')->name('status')->middleware(['can:view_leave']);
        Route::get('/request', 'create')->name('request')->middleware(['can:request_leave']);
        Route::post('/create', 'store')->name('store')->middleware(['can:request_leave']);
        Route::get('/{leave}', 'show')->name('show')->middleware(['can:view_leave']);
        Route::get('/{leave}/edit', 'edit')->name('edit')->middleware(['can:edit_leave']);
        Route::put('/{leave}', 'update')->name('update')->middleware(['can:edit_leave']);
        Route::delete('/{leave}', 'destroy')->name('destroy')->middleware(['can:delete_leave']);
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
        Route::get('/show/{leave}', 'show')->name('show')->middleware(['can:view_leave_requests']);
        Route::get('/index', 'index')->name('index')->middleware(['can:view_leave_requests']);
        Route::post('/inspect/{leave}', 'inspect')->name('inspect')->middleware(['can:respond_leave_request']);
    });


// Managing frequencies
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/frequencies')
    ->controller(EmployeeManageAllowanceFrequencyController::class)
    ->name('employee.manage.frequencies.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
    });


// Managin Allowances

Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/allowance')
    ->controller(EmployeeManageAllowancesController::class)
    ->name('employee.manage.allowances.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware(['can:view_allowances']);
        Route::get('/create', 'create')->name('create')->middleware(['can:create_allowances']);
        Route::post('/store', 'store')->name('store')->middleware(['can:create_allowances']);
        Route::get('/{allowance}/edit', 'edit')->name('edit')->middleware(['can:edit_allowances']);
        Route::put('/{allowance}', 'update')->name('update')->middleware(['can:edit_allowances']);
        Route::delete('/{allowance}', 'destroy')->name('destroy')->middleware(['can:delete_allowances']);
    });


// Managin Loans

Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/loans')
    ->controller(EmployeeManageLoansController::class)
    ->name('employee.manage.loans.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware(['can:view_loans']);
        Route::get('/create', 'create')->name('create')->middleware(['can:create_loans']);
        Route::post('/store', 'store')->name('store')->middleware(['can:create_loans']);
        Route::get('/{loan}/edit', 'edit')->name('edit')->middleware(['can:edit_loans']);
        Route::put('/{loan}', 'update')->name('update')->middleware(['can:edit_loans']);
        Route::delete('/{loan}', 'destroy')->name('destroy')->middleware(['can:delete_loans']);
    });


// Employee Allowances Start here
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/{employee}/allowances')
    ->controller(EmployeeManageEmployeeAllowancesController::class)
    ->name('employee.manage.employee.allowances.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('can:view_allowances');
        Route::get('/create', 'create')->name('create')->middleware('can:create_allowances');
        Route::post('/', 'store')->name('store')->middleware('can:create_allowances');
        Route::get('/{allowance_id}/edit', 'edit')->name('edit')->middleware('can:edit_allowances');
        Route::put('/{allowance_id}', 'update')->name('update')->middleware('can:edit_allowances');
        Route::delete('/{allowance_id}', 'destroy')->name('destroy')->middleware('can:delete_allowances');
        Route::put('{allowance}/toggle-status', 'toggleStatus')->name('toggleStatus')->middleware(['can:edit_allowances']);
    });


// Employee Allowances Group Routes Starts
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/allowances/groups')
    ->controller(EmployeeAllowanceGroupController::class)
    ->name('employee.manage.employee.allowances.groups.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('/members/{group}', 'getGroupMembers')->name('members');
        Route::get('/assign-allowance/{group}', 'getGroupMembersToAssignAllowance')->name('assign');

        // group allawances details routes
        Route::get('/{group}/allowances/{allowance}', 'getGroupAllowanceDetails')->name('allowanceDetails');
        Route::get('/{group}/categories/{category}/edit/members', 'editMembers')->name('editMengers');
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
        // report routes
    });
// Leave reports
Route::prefix('employee/manage/leave/reports')
    ->controller(EmployeeManageLeavesController::class)
    ->name('employee.manage.leave.reports.')
    ->group(function () {
        Route::view('/report', 'employee.manage.leaves.reports.index')->name('reports');

        Route::get('/rejected', 'getRejectedLeavesPage')->name('rejected');
        Route::get('/accepted', 'getAcceptedLeavesPage')->name('accepted');
        Route::get('/pending', 'getPendingLeavesPage')->name('pending');
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
        Route::get('/employees', 'getEmployees')->name('getEmployees')->middleware(['can:create_payroll']);
        Route::put('/{payroll}', 'update')->name('update')->middleware(['can:edit_payroll']);
        Route::delete('/{payroll}', 'destroy')->name('destroy')->middleware(['can:delete_payroll']);
        Route::get('/{payroll}', 'show')->name('show')->middleware(['can:view_payroll']);
    });


//  Employee Management Routes
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
        Route::patch('/UpdatePayGrade/{employee}', 'UpdatePayGrade')->name('UpdatePayGrade')->middleware(['can:edit_employees']);
        Route::post('/excel/import', 'excelImport')
            ->name('excel.import');
        // route to update Employee status
        Route::post('/{employee}/toggle-status', 'updateStatus')
            ->name('updateStatus')
            ->middleware(['can:edit_employee_status']);
    });


//Employee Reports
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/employee/reports')
    ->controller(EmployeeManageEmployeeController::class)
    ->name('employee.manage.employees.reports.')
    ->group(function () {
        Route::view('/report', 'employee.manage.employee.reports.index')->name('index');
        Route::get('/suspended', 'getSuspendedEmployeesPage')->name('suspended');
        Route::get('/active', 'getActiveEmployeesPage')->name('active');
        Route::get('/terminated', 'getTerminatedEmployeesPage')->name('terminated');
        Route::get('/on-leave', 'getOnLeaveEmployeesPage')->name('onLeave');
        Route::get('/resigned', 'getResignedEmployeesPage')->name('resigned');
    });



Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/{employee}/deductions')
    ->controller(EmployeeManageDeductionController::class)
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


Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/disbursements')
    ->controller(EmployeeManageDisbursements::class)
    ->name('employee.manage.disbursements.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create')
            ->middleware(['password.confirm']);
        Route::post('/', 'store')->name('store');
        // Route::get('/{disbursement}', 'show')->name('show')->middleware(['can:view_disbursement']);            // Show a single disbursement
        // Route::put('/{disbursement}', 'update')->name('update')->middleware(['can:edit_disbursement']);        // Update disbursement
        // Route::delete('/{disbursement}', 'destroy')->name('destroy')->middleware(['can:delete_disbursement']);   // Delete disbursement
    });


// Payroll Employee Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/payroll/employee')
    ->name('employee.manage.payroll.employees.')
    ->controller(EmployeeManagePayrollEmployeeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/pending', 'pending')->name('pending');
        Route::get('/approved', 'approved')->name('approved');
        Route::get('/rejected', 'rejected')->name('rejected');

        Route::post('/{payroll}/reject', 'reject')->name('reject');
    });
// payroll report routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/payroll/employee/reports')
    ->name('employee.manage.payroll.employees.reports.')
    ->controller(EmployeeManagePayrollEmployeeController::class)
    ->group(function () {
        Route::view('/', 'employee.manage.payroll.reports.index')->name('index');
    });

Route::get('/employees/{employee}/payrolls/{payroll}', [EmployeeManagePayrollController::class, 'show'])->name('payroll.show');


// attendance route
Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/attendance')
    ->name('employee.manage.attendance.')
    ->controller(EmployeeManageAttendanceController::class)
    ->group(function () {
        Route::get('/dashboard', 'dashboard')
            ->name('dashboard');
        Route::get('/manual/entry', 'manualEntryPage')
            ->name('manualEntry');
        Route::get('/daily/attendance', 'dailyAttendance')
            ->name('dailyAttendance');
        Route::post('/manual/entry/store', 'manualEntryStore')
            ->name('manual.entry.store');
    });



Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/attendance')
    ->name('employee.attendance.')
    ->controller(EmployeeAttendancesController::class)
    ->group(function () {
        Route::get('/dashboard', 'dashboard')
            ->name('dashboard');
    });
    