<?php

use App\Http\Controllers\Owner\OwnerCompaniesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:OWNER'])
    ->prefix('/owner/companies')
    ->controller(OwnerCompaniesController::class)
    ->name('owner.companies.')
    ->group(function () {
        Route::get('/all', 'companiesAll')->name('all');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/addAdmin', 'addAdmin')->name('addAdmin');
        Route::post('/toggleStatus/{id}', 'toggleStatus')->name('toggleStatus');
    });
