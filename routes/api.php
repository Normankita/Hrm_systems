<?php

use App\Http\Controllers\Api\ApiAllowanceGroupsController;
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
        Route::post('/remove/employees/to/group/{id}', 'removeMembersToGroup')->name('remove.employees.to.group');
    });

