<?php

namespace App\Http\Utils\Traits;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

trait EmployeeTrait {

    /**
     * Summary of createEmployee
     * @param mixed $data
     * @return Employee
     */
    public  static  function createEmployee($data): Employee{
        // start by creating a user account first
        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make(strtolower($data['last_name'])),
            'company_id' => $data['company_id'],
        ]);
        $employeeRole = Role::findById($data['role_id']);
        $user->assignRole($employeeRole);
        $data['user_id'] = $user->id;
        $employee = Employee::create($data);
        return $employee;
    }


    public static function getEmployeeById($id): Employee {
        // Find the employee by ID
        $employee = Employee::find($id);
        return $employee;
    }

    public static function updateEmployee($id, $data)
{
    $employee = Employee::find($id);

    if (!$employee) {
        return null; // or throw exception
    }

    $user = User::find($employee->user_id);

    if ($user) {
        $user->update([
            'name' => $data['full_name'] ?? ($data['first_name'] . ' ' . $data['last_name']),
            'email' => $data['email'],
        ]);
    }

    $employee->update($data);

    return $employee;
}


}
