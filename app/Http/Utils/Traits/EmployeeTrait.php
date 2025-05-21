<?php

namespace App\Http\Utils\Traits;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        $employeeRole = Role::where('name', 'EMPLOYEE')->first();
        $extraRole = Role::findById($data['role_id']);
        $roles = [$employeeRole, $extraRole];
        $user->assignRole($roles);
        $data['user_id'] = $user->id;
        $employee = Employee::create($data);
        self::assignActivePaygradeToEmployee(
            $employee->id,
            $data['pay_grade_id'],
            [
                'assigned_by' => Auth::user()->id,
                'effective_from' => now(),
                'base_salary_override' => $data['base_salary_override'] ? $data['base_salary_override'] : 0,
            ]
        );
        return $employee;
    }


    public static function getEmployeeById($id): Employee
    {
        // Find the employee by ID
        $employee = Employee::with(['pay_grades', 'attachments', 'payrolls'])->findOrFail($id);
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
        $employee = Employee::find($id);
        if (!$employee) {
            return null;
        }

        // Update user record
        $user = User::find($employee->user_id);
        if ($user) {
            $user->update([
                'name' => $data['full_name'] ?? ($data['first_name'] . ' ' . $data['last_name']),
                'email' => $data['email'],
            ]);
            if (isset($data['role_id'])) {
                $newRole = Role::findById($data['role_id']);
                $employeeRole = Role::where('name', 'EMPLOYEE')->first();
                $user->syncRoles([$newRole, $employeeRole]);
            }
        }

        $employee->update($data);

        if (isset($data['pay_grade_id'])) {
            self::assignActivePaygradeToEmployee(
                $employee->id,
                $data['pay_grade_id'],
                [
                    'assigned_by' => Auth::user()->id,
                    'effective_from' => $data['effective_from'],
                    'base_salary_override' => $data['base_salary_override'],
                ]
            );
        }

        return $employee;
    }


    /**
     * Assign a new active paygrade to an employee. First deactivate all current
     * pay_grades, then check if the new paygrade already exists, update or
     * attach new.
     *
     * @param int $employeeId
     * @param int $paygradeId
     * @return void
     */
    public static function assignActivePaygradeToEmployee($employeeId, $paygradeId, array $extra = [])
    {
        $employee = Employee::findOrFail($employeeId);

        // Deactivate all current paygrades
        $employee->pay_grades()->updateExistingPivot(
            $employee->pay_grades->pluck('id')->toArray(),
            ['status' => false]
        );

        // Add the "status" to the extra pivot data
        $pivotData = array_merge($extra, ['status' => true]);

        if ($employee->pay_grades->contains($paygradeId)) {
            $employee->pay_grades()->updateExistingPivot($paygradeId, $pivotData);
        } else {
            $employee->pay_grades()->attach($paygradeId, $pivotData);
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
