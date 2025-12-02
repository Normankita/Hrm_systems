<?php

use App\Http\Controllers\AdminControllers\AdminAllowanceGroupController;
use App\Http\Controllers\AdminControllers\AdminAttendancesController;
use App\Http\Controllers\AdminControllers\AdminAttendanceSessionsController;
use App\Http\Controllers\AdminControllers\AdminManageAllowancesController;
use App\Http\Controllers\AdminControllers\AdminManageDisbursements;
use App\Http\Controllers\AdminControllers\AdminManageEmployeeAllowancesController;
use App\Http\Controllers\AdminControllers\AdminManageLeaveTypeController;
use App\Http\Controllers\AdminControllers\AdminRoleController;
use App\Http\Controllers\AdminControllers\AdminCompanyController;
use App\Http\Controllers\AdminControllers\AdminDepartmentController;
use App\Http\Controllers\AdminControllers\AdminEmployeeController;
use App\Http\Controllers\AdminControllers\AdminManageAllowanceFrequencyController;
use App\Http\Controllers\AdminControllers\AdminManageLeavesController;
use App\Http\Controllers\AdminControllers\AdminPayGradeController;
use App\Http\Controllers\AdminControllers\AdminPayrollEmployeeController;
use App\Http\Controllers\AdminControllers\AdminPermissionsController;
use App\Http\Controllers\AdminControllers\AdminSettingController;
use App\Http\Controllers\Api\ApiRolesController;
use App\Http\Controllers\EmployeeControllers\EmployeePayGradeController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('/admin/employee/permissions')
    ->controller(AdminPermissionsController::class)
    ->name('admin.employees.')
    ->group(function () {
        Route::get('/all', 'permissionsAll')->name('permissions.all');
        Route::get('/edit/permissions/{id}', 'editPermissions')
            ->name('edit.permissions');
    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('/admin/employee')
    ->controller(AdminEmployeeController::class)
    ->name('admin.employees.')
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
        Route::post('/excel/import', 'excelImport')
            ->name('excel.import');
    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('/admin/department')
    ->controller(AdminDepartmentController::class)
    ->name('admin.departments.')
    ->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::patch('/update/{id}', 'update')->name('update');
    });


Route::middleware(['auth', 'role:ADMIN'])
    ->prefix('/admin/company')
    ->controller(AdminCompanyController::class)
    ->name('admin.companies.')
    ->group(function () {
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('/admin/role')
    ->controller(AdminRoleController::class)
    ->name('admin.roles.')
    ->group(function () {
        Route::put('/update/{id}', 'update')
            ->name('update');

        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');

        Route::get('/edit/permissions/{id}', 'editPermissions')
            ->name('edit.permissions');

        Route::get('/get/employees', 'getEmployeesPage')
            ->name('get.employees.page');

        Route::get('/get/employees/{id}/permissions/page', 'assignPermissionsPage')
            ->name('get.assign.permissions.page');

        Route::post('/assign/permissions/{id}', 'assignPermissions')
            ->name('assign.permissions');

    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('/admin/permission')
    ->controller(ApiRolesController::class)
    ->name('admin.permissions.')
    ->group(function () {
        Route::put('/role/update/{id}', 'updateRolePermissions')
            ->name('role.update');
        Route::put('/user/update/{id}', 'updateUserPermissions')
            ->name('user.update');
    });


Route::middleware(['auth', 'role:ADMIN'])
    ->prefix('/admin/settings')
    ->controller(AdminSettingController::class)
    ->name('admin.settings.')
    ->group(function () {
        Route::post('/store', 'store')->name('store')
            ->middleware('HasCompanyProfile');
        Route::get('/', 'index')->name('index')
            ->middleware('HasCompanyProfile');
        Route::put('/update/{id}', 'update')->name('update')
            ->middleware('HasCompanyProfile');
    });


Route::middleware(['auth', 'role:ADMIN'])
    ->prefix('/admin/attendance')
    ->controller(AdminAttendancesController::class)
    ->name('admin.attendances.')
    ->group(function () {
        Route::post('/store', 'store')->name('store')
            ->middleware('HasCompanyProfile');
        Route::get('/', 'index')->name('index')
            ->middleware('HasCompanyProfile');
        Route::get('/daily/page', 'dailyAttendancePage')
            ->name('daily.page')
            ->middleware('HasCompanyProfile');
        Route::get('/manual/entry', 'manualEntryPage')
            ->name('manual.entry.page')
            ->middleware('HasCompanyProfile');
        Route::post('/manual/entry/store', 'manualEntryStore')
            ->name('manual.entry.store')
            ->middleware('HasCompanyProfile');
        Route::delete('/delete/{id}', 'destroy')
            ->name('delete')
            ->middleware('HasCompanyProfile');
        Route::put('/update/{id}', 'update')
            ->name('update')
            ->middleware('HasCompanyProfile');
    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('/admin/attendance/sessions')
    ->controller(AdminAttendanceSessionsController::class)
    ->name('admin.attendances.sessions.')
    ->group(function () {
        Route::get('/', 'index')->name('index')
            ->middleware('HasCompanyProfile');
        Route::post('/store', 'store')->name('store')
            ->middleware('HasCompanyProfile');
        Route::put('/update/{id}', 'update')
            ->name('update')->middleware('HasCompanyProfile');
        Route::get('/dashboard', 'getSessionDashboard')
            ->name('get.dashboard')->middleware('HasCompanyProfile');
    });


// Leave Types
Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('admin/leave/type')
    ->controller(AdminManageLeaveTypeController::class)
    ->name('admin.leave.type.')
    ->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::put('/update/{leaveType}', 'update')->name('update');
        Route::delete('/destroy/{leaveType}', 'destroy')->name('destroy');
        // report routes
    });


// PayGrade Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('admin/paygrade')
    ->name('admin.paygrades.')
    ->controller(AdminPayGradeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{payGrade}', 'show')->name('show');
        Route::post('/store', 'store')->name('store');
        Route::patch('/update/{payGrade}', 'update')->name('update');
        Route::delete('/delete/{payGrade}', 'destroy')->name('delete');
        Route::get('/edit/{payGrade}', 'edit')->name('edit');
    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('admin/leave')
    ->controller(AdminManageLeavesController::class)
    ->name('admin.leave.')
    ->group(function () {
        Route::get('/show/{leave}', 'show')->name('show');
        Route::get('/index', 'index')->name('index');
        Route::post('/inspect/{leave}', 'inspect')->name('inspect');
    });


// Payroll Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('admin/payrolls')
    ->name('admin.payrolls.')
    ->controller(AdminPayrollEmployeeController::class)
    ->group(function () {
        Route::get('/', 'manageIndex')->name('index');
        Route::post('/generate-all', 'generateAll')->name('generateAll');
        Route::get('/{payroll}/edit', 'edit')->name('edit');
        Route::get('/{payroll}', 'show')
            ->name('show');
        Route::get('/employees', 'getEmployees')->name('getEmployees');
        Route::put('/{payroll}', 'update')->name('update');
        Route::delete('/{payroll}', 'destroy')->name('destroy');
        Route::get('/single/group/{reference}', 'singleGroupShow')->name('singleGroup.show');
    });


// Payroll Employee Routes
Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('admin/payroll/employee')
    ->name('admin.payroll.employees.')
    ->controller(AdminPayrollEmployeeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/display/pending', 'pending')->name('pending');
        Route::get('/display/approved', 'approved')->name('approved');
        Route::get('/display/rejected', 'rejected')->name('rejected');

        Route::post('/{payroll}/reject', 'reject')->name('reject');
        Route::view('/reports', 'admin.payroll.reports.index')->name('manageIndex');
    });


Route::prefix('admin/leave/reports')
    ->controller(AdminManageLeavesController::class)
    ->name('admin.leave.reports.')
    ->group(function () {
        Route::view('/report', 'admin.leaves.reports.index')->name('reports');

        Route::get('/rejected', 'getRejectedLeavesPage')->name('rejected');
        Route::get('/accepted', 'getAcceptedLeavesPage')->name('accepted');
        Route::get('/pending', 'getPendingLeavesPage')->name('pending');
    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('admin/allowance')
    ->controller(AdminManageAllowancesController::class)
    ->name('admin.allowances.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{allowance}/edit', 'edit')->name('edit');
        Route::put('/{allowance}', 'update')->name('update');
        Route::delete('/{allowance}', 'destroy')->name('destroy');
    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('admin/allowances/groups')
    ->controller(AdminAllowanceGroupController::class)
    ->name('admin.employee.allowances.groups.')
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


Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('admin/frequencies')
    ->controller(AdminManageAllowanceFrequencyController::class)
    ->name('admin.frequencies.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:ADMIN'])
    ->prefix('admin/disbursements')
    ->controller(AdminManageDisbursements::class)
    ->name('admin.disbursements.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create')
            ->middleware(['password.confirm']);
        Route::post('/store', 'store')->name('store');
        Route::get('/view/disbursed/group', 'viewDisbursementsGroup')
            ->name('group.view');
    });
