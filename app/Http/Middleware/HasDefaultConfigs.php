<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasDefaultConfigs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if( Auth::user()->hasRole('EMPLOYEE')) {
                if (!Auth::user()->is_default_configs) {
                    return redirect()->route('employees.profile.edit_password', Auth::user()->id)
                        ->withErrors('Please Fill The Default Configurations To Continue.');
                }
            }
        }
        return $next($request);
    }
}
