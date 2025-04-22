<?php

use App\Models\Employee;

trait EmployeeTrait {

    /**
     * Summary of createEmployee
     * @param mixed $data
     * @return Employee
     */
    public  static  function createEmployee($data): Employee{
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
