<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('role_type', 'general')
            ->orWhere('company_id', auth()->user()->company_id)
            ->get();
        dd($roles);
        return view('admin.roles.index', compact('roles'));
    }
}
