<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'HasCompanyProfile', 'HasDefaultConfigs'])
    ->get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

require __DIR__. '/admin.php';

require __DIR__. '/hr.php';

require __DIR__. '/payroll.php';

require __DIR__. '/employee.php';

require __DIR__ . '/auth.php';

require __DIR__ . '/api.php';
