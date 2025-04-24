<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule as ValidationRule;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('role_type', 'specific')
            ->orWhere(function($query) {
                $query->where('company_id', '!=', null)
                    ->orWhere('company_id', auth()->user()->company_id);
            })
            ->orderBy('name')
            ->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255', ValidationRule::unique('roles')->where(function ($query) {
                return $query->where('company_id', Auth::user()->company_id);
            })],
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'company_id' => Auth::user()->company_id,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }
}
