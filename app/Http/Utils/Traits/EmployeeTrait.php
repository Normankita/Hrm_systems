<?php

namespace App\Http\Utils\Traits;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

trait EmployeeTrait
{

    /**
     * Summary of createEmployee
     * This function takes the employee data and creates a new employee
     * @param array $data
     * @return Employee
     */
    public static function createEmployee($data): Employee
    {
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
        self::assignActivePaygradeToEmployee($employee->id, $data['pay_grade_id']);
        return $employee;
    }


    public static function getEmployeeById($id): Employee
    {
        // Find the employee by ID
        $employee = Employee::with(['paygrades', 'attachments', 'payrolls'])->findOrFail($id);
        return $employee;
    }

    /**
     * Updates an existing employee.
     *
     * @param int $id
     * @param array $data
     * @return Employee|null
     */
    public static function updateEmployee($id, $data)
    {
        // Find the employee by ID
        $employee = Employee::find($id);
        if (!$employee) {
            return null; // or throw exception
        }

        // Update user record
        $user = User::find($employee->user_id);
        if ($user) {
            $user->update([
                'name' => $data['full_name'] ?? ($data['first_name'] . ' ' . $data['last_name']),
                'email' => $data['email'],
            ]);
        }

        // Update employee record
        $employee->update($data);

        // If pay_grade_id is provided, assign a new active paygrade to the employee
        if (isset($data['pay_grade_id'])) {
            self::assignActivePaygradeToEmployee($employee->id, $data['pay_grade_id']);
        }
        return $employee;
    }

    /**
     * Assign a new active paygrade to an employee. First deactivate all current
     * paygrades, then check if the new paygrade already exists, update or
     * attach new.
     *
     * @param int $employeeId
     * @param int $paygradeId
     * @return void
     */
    public static function assignActivePaygradeToEmployee($employeeId, $paygradeId)
    {
        $employee = Employee::findOrFail($employeeId);

        // 1. Deactivate all current paygrades
        $employee->paygrades()->updateExistingPivot(
            $employee->paygrades->pluck('id')->toArray(),
            ['status' => false]
        );

        // 2. Check if already exists, update or attach new
        if ($employee->paygrades->contains($paygradeId)) {
            $employee->paygrades()->updateExistingPivot($paygradeId, ['status' => true]);
        } else {
            $employee->paygrades()->attach($paygradeId, ['status' => true]);
        }
    }




    private function getNamesFromFullName($fullName): array
    {
        // Split full name
        $nameParts = explode(' ', $fullName, 3); // Only split into 3 parts: first and last
        $first_name = $nameParts[0];
        $middle_name = $nameParts[1] ?? '';
        $last_name = $nameParts[2] ?? '';
        $nameParts = [

            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
        ];
        return $nameParts;
    }



}
