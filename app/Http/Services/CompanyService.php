<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PayGrade;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class CompanyService extends Controller
{
    public static function createCompany($details)
    {

         // validation rules
        $rules = [
            'name' => 'required|string|max:255|unique:companies,name',
            'email' => 'required|string|email|max:255|unique:companies,email',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255|unique:companies,contact_number',
            'brela_reg_number' => 'required|string|max:255|unique:companies,brela_reg_number',
            'tin_number' => 'required|string|max:255|unique:companies,tin_number',
        ];
        // validate the incomming data
        $validated = Validator::make($details, $rules);
        if ($validated->fails()) {
            return [
                'status' => 'error',
                'type' => 'validation',
                'validated' => $validated
            ];
        }

        $company = Company::create($details);

        $settings = [
            ['name' => 'payment_date', 'value' => 27]
        ];
        foreach ($settings as $setting) {
            $setting = array_merge(['company_id' => $company->id], $setting);
            Setting::create($setting);
        }

        PayGrade::create([
            'name' => 'Default Grade',
            'base_salary' => 50000,
            'max_salary' => 300000,
            'base_month_count' => 12,
            'company_id' => $company->id,
            'description' => 'Default pay grade for initial employees',
        ]);

        return [
            'status' => 'success',
            'message' => 'Company created successfully',
        ];

    }


    public function companyRoles(Company $company)
    {
        $roles = array(
            [
                'name' => 'ADMIN',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EMPLOYEE',
                'guard_name' => 'web',
            ],
        );

        foreach ($roles as $role) {
            $role = array_merge(
                $role,
                ['company_id' => $company->id]
            );
            Role::create($role);
        }
    }


    public function givePermissionsToCompanyAdminRole(
        Company $company)
    {
        // Assign permissions to roles
        $adminRole = $company->roles()
            ->where('name', 'ADMIN')
            ->first();
        if (!$adminRole) {
            return [
                'status' => 'error',
                'message' => 'Admin role not found'
            ];
        }
        $permissions = Permission::all()
            ->pluck('name')->toArray();

        // create the actual permissions
        foreach ($permissions as $permission) {
            Permission::create($permission);
            $adminRole->givePermissionTo($permission['name']);
        }
    }
}

