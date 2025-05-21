<?php

namespace App\Providers;

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
        // Define custom Gate for checking any permission with Spatie
        Gate::define('have-any-permission', function (User $user, ...$permissions) {
            $userHasPermissin = $user->hasAnyPermission($permissions);
            $roles = $user->roles();
            foreach ($roles as $role) {
                if ($role->hasAnyPermission($permissions)) {
                    return true;
                }
            }
            return $userHasPermissin;
        });
    }
}
