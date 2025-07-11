<?php

use App\Http\Controllers\Api\ApiAllowanceGroupMembersController;
use App\Http\Controllers\Api\ApiAllowanceGroupsController;
use App\Http\Controllers\Api\ApiDisbursementsController;
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
        Route::post('/add/employees/to/group/{group}/allowance/{allowance}',
            'addMemberToGroupAllowance')->name('add.employees.to.group.allowance');
    });


Route::prefix('disbursements')
    ->controller(ApiDisbursementsController::class)
    ->name('disbursements.')
    ->group(function () {
        Route::get('/categorized', 'fetchCategoryWise')
            ->name('categorized');
        Route::post('/disburse', 'disburse')->name('disburse');
    });
