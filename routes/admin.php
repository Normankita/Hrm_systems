<?php

use App\Http\Controllers\AdminControllers\AdminRoleController;
use App\Http\Controllers\AdminControllers\AdminCompanyController;
use App\Http\Controllers\AdminControllers\AdminDepartmentController;
use App\Http\Controllers\AdminControllers\AdminEmployeeController;
use App\Http\Controllers\AdminControllers\AdminSettingController;
use App\Http\Controllers\Api\ApiRolesController;
use Illuminate\Support\Facades\Route;


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


Route::middleware(['auth', 'role:ADMIN'])
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
    });



Route::middleware(['auth', 'role:ADMIN'])
    ->prefix('/admin/role')
    ->controller(ApiRolesController::class)
    ->name('admin.roles.')
    ->group(function () {
        Route::put('/update/permissions/{id}', 'updatePermissions')
            ->name('permissions.update');
    });


Route::middleware(['auth', 'role:ADMIN'])
    ->prefix('/admin/settings')
    ->controller(AdminSettingController::class)
    ->name('admin.settings.')
    ->group(function () {
        Route::post('/store', 'store')->name('store');
        Route::get('/', 'index')->name('index');
        Route::put('/update/{id}', 'update')->name('update');
    });
