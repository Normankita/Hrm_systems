<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ApiRolesController extends Controller
{
    public function __construct()
    {

    }

    public function updatePermissions(Request $request, $id)
    {
        // Validate that permissions is a nullable array and each ID exists
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Find the role
        $role = Role::findOrFail($id);

        // If permissions is empty or null, sync an empty array to remove all permissions
        if (empty($request->permissions)) {
            $role->syncPermissions([]);
        } else {
            // Get permissions by IDs and extract names
            $permissionsCollection = Permission::whereIn('id', $request->permissions)->get();
            $permissionNames = $permissionsCollection->pluck('name')->toArray();
            $role->syncPermissions($permissionNames);
        }

        return response()->json([
            'message' => 'Permissions updated successfully',
            'role' => $role,
        ]);
    }
}
