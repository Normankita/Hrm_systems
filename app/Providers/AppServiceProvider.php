<?php

namespace App\Providers;

require_once app_path('Helpers/AttendanceHelper.php');

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('check-attendance', function ($user) {
            return canCheckAttendance();
        });
        require_once app_path('Helpers/CurrencyHelper.php');
    }
}
