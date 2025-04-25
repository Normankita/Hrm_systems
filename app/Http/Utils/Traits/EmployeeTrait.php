<?php

namespace App\Http\Utils\Traits;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
        $data['user_id'] = $user->id;
        $employee = Employee::create($data);
        return $employee;
    }


    public static function getEmployeeById($id): Employee {

        // $employee = Employee::with([
        //     'company',
        //     'department',
        //     'designation.designation', // Accessing nested DesignationRoleMapping -> Designation
        //     'employeeContract.supervisor'
        // ])->findOrFail($id);
        $employee = Employee::find($id);
        return $employee;
    }

}
