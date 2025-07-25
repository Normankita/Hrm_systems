<?php

use App\Enums\AllowanceGroups;
use App\Http\Controllers\OwnerControllers\OwnerUsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('guest')->name('home');

Route::middleware(['auth', 'HasCompanyProfile', 'HasDefaultConfigs'])
    ->get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

Route::controller(OwnerUsersController::class)
    ->prefix('/owner')
    ->group(function () {
        Route::get('/users/', 'index');
    });

require __DIR__ . '/admin.php';

require __DIR__ . '/hr.php';

require __DIR__ . '/payroll.php';

require __DIR__ . '/employee.php';

require __DIR__.'/owner.php';

require __DIR__ . '/auth.php';

require __DIR__ . '/api.php';


Route::get('/test', function () {
    $categories = \App\Models\Allowance::limit(1)->get();
    $basedOn = AllowanceGroups::CATEGORY;
    $service = new \App\Http\Services\AllowanceDisbursementService();
    $result = $service->handleDisbursement($basedOn, $categories->pluck('id')->toArray());
})->name('test');
