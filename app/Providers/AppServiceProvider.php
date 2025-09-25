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
        Gate::define('permissions_or_roles', function ($user, array $options) {
            $roles = $options['roles'] ?? [];
            $permissions = $options['permissions'] ?? [];

            // Check roles
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    return true;
                }
            }

            // Check permissions
            foreach ($permissions as $permission) {
                if ($user->can($permission)) {
                    return true;
                }
            }

            // No role or permission matched
            return false;
        });
        require_once app_path('Helpers/CurrencyHelper.php');
    }
}
