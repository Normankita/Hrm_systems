<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule as ValidationRule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{

    /**
     * Summary of index
     * @return View
     */
    public function index()
    {
        $roles = Role::where('role_type', 'specific')
            ->orWhere(function ($query) {
                $query->where('company_id', '!=', null)
                    ->orWhere('company_id', auth()->user()->company_id);
            })
            ->orderBy('name')
            ->get();
        return view('admin.roles.index', compact('roles'));
    }



    /**
     * Summary of store
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                ValidationRule::unique('roles')->where(function ($query) {
                    return $query->where('company_id', Auth::user()->company_id);
                })
            ],
        ]);
        Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'company_id' => Auth::user()->company_id,
        ]);
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }



    /**
     * Summary of update
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                ValidationRule::unique('roles')->ignore($role->id)
                    ->where(function ($query) {
                        return $query->where('company_id', Auth::user()->company_id);
                    })
            ],
        ]);
        $role->update([
            'name' => $request->name,
        ]);
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }



    /**
     * Summary of editPermissions
     * @param int $id
     * @return View
     */
    public function editPermissions(int $id): View
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('admin.roles.manage_role',
             compact('role', 'permissions'));
    }

}
