<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminEmployeeController extends Controller
{
    public function create()
    {
        $roles = Role::where('name', '!=', 'ADMIN');
        return view('admin.employee.create')
            ->with('roles', $roles);
    }

    public function store(Request $request) {
        $rules = array(
            ['role_id' => 'requered'],
            ['department_id'],
            ['company_id'],
            ['first_name'],
            ['last_name'],
            ['email'],
            ['phone']
        );
    }
}
