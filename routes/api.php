<?php

use App\Http\Controllers\Api\ApiAllowanceGroupMembersController;
use App\Http\Controllers\Api\ApiAllowanceGroupsController;
use App\Http\Controllers\Api\ApiAttendanceController;
use App\Http\Controllers\Api\ApiDisbursementsController;
use App\Http\Controllers\Api\ApiEmployeePayrollController;
use App\Http\Controllers\Api\ApiEmployeesController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/users', function () {
    return response()->json(User::all());
})->name('api.roles.index');


Route::prefix('groups')
    ->controller(ApiAllowanceGroupsController::class)
    ->name('groups.')
    ->group(function () {
        Route::post('/add/employees/to/group/{id}', 'addMembersToGroup')->name('add.employees.to.group');
        Route::post('/remove/employees/from/group/{id}', 'removeMembersFromGroup')->name('remove.employees.to.group');
        Route::post('/assign/allowance/to/group/{id}', 'assignAllowanceToGroup')->name('assig.allowance.to.group');
    });


Route::prefix('groups')
    ->controller(ApiAllowanceGroupMembersController::class)
    ->name('groups.')
    ->group(function () {
        Route::post(
            '/add/employees/to/group/{group}/allowance/{allowance}',
            'addMemberToGroupAllowance'
        )->name('add.employees.to.group.allowance');
    });


Route::prefix('disbursements')
    ->controller(ApiDisbursementsController::class)
    ->name('disbursements.')
    ->group(function () {
        Route::get('/categorized', 'fetchCategoryWise')
            ->name('categorized');
        Route::post('/disburse', 'disburse')->name('disburse');
    });


Route::middleware(['auth', 'HasCompanyProfile', 'role:EMPLOYEE'])
    ->prefix('employee/manage/payrolls')
    ->name('employee.manage.payrolls.')
    ->controller(ApiEmployeePayrollController::class)
    ->group(function () {
        Route::post('/generate', 'generateForSelected')->name('generateSelected');
        Route::post('/approve/selected', 'approveSelected')->name('approveSelected');
    });


Route::middleware('auth', 'admin-or-attendance')
    ->prefix('/attendance')
    ->controller(ApiAttendanceController::class)
    ->name('attendances.')
    ->group(function () {
        Route::post('/manual/entry/store', 'manualEntryStore')
            ->name('manual.entry.store')
            ->middleware('HasCompanyProfile');
    });


Route::middleware([])->group(function () {
    // Additional authenticated API routes can be added here
    Route::post('/disburse/allowance/grouped', [
        ApiDisbursementsController::class,
        'disburseGrouped'
    ])
        ->name('disburse.allowance.grouped');
});




Route::middleware([])->group(function () {
    // Additional authenticated API routes can be added here
    Route::post('/disburse/allowance/individual/in/grouped', [
        ApiDisbursementsController::class,
        'disburseIndividualInGroup'
    ])
        ->name('disburse.allowance.individual.in.grouped');
});


Route::middleware(['auth'])->group(function () {
    // Additional authenticated API routes can be added here
    Route::post('/fetch/employees', [
        ApiEmployeesController::class,
        'fetchEmployees'
    ])
        ->name('fetch.employees');
});

