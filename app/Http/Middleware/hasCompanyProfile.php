<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class hasCompanyProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // You can adjust the check depending on how your user-company relation works
        if (!$user || !$user->company || !$user->company->isActive) {
            return redirect()->route('admin.companies.edit', $user->company->id)
                ->withErrors('Please Fill The Company Details To Continues.');
        }

        if(!$user->company->address || !$user->company->contact_number ||
            !$user->company->email || !$user->company->brela_reg_number ||
            !$user->company->tin_number) {
            // Redirect to the company edit page if the company details are not filled
            return redirect()->route('admin.companies.edit', $user->company->id)
                ->withErrors('Please Fill The Company Details To Continues.');
        }

        return $next($request);
    }
}
