<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class AdminPermissionsController extends Controller
{
        public function permissionsAll()
    {
        $employees = Auth::user()->company->employees()
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.permissions.employee.employee-list', compact('employees'));
    }


        public function editPermissions(int $id)
    {
        $employee = Employee::findOrFail($id);
        $permissions = Permission::all();
        return view('admin.permissions.employee.manage-user-permissions',
             compact('employee', 'permissions'));
    }

}
