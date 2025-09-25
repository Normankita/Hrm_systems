<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrAttendancePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()?->hasRole('ADMIN') || auth()->user()?->can('mark_attendance')) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}