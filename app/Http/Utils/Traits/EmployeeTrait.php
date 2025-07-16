<?php

namespace App\Http\Utils\Traits;

use App\Models\Employee;
use App\Models\EmployeeStatusHistory;
use App\Models\PayGrade;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Throwable;

trait EmployeeTrait
{
    public static function createEmployee($data): Employee
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make(strtolower($data['last_name'])),
                'company_id' => $data['company_id'],
            ]);
            $employeeRole = Role::where('name', 'EMPLOYEE')->first();
            $extraRole = array_key_exists('role_id', $data) ? Role::findById($data['role_id']) : null;
            $roles = $extraRole ? [$employeeRole, $extraRole] : [$employeeRole];
            $user->assignRole($roles);
            $data['user_id'] = $user->id;
            $employee = Employee::create($data);
            $employee->recordEvent('add', $data);
            $status = Status::where('name', 'Active')->first();
            EmployeeStatusHistory::create([
                'employee_id' => $employee->id,
                'status_id' => $status->id,
                'is_active' => true,
                'effective_date' => now(),
                'assigned_by' => Auth::user()->id,
                'reason' => 'Got hired',
            ]);
            $PayGrade = PayGrade::find($data['pay_grade_id']);
            $newSalaryOverride = $data['base_salary_override'] ? $data['base_salary_override'] : $PayGrade->base_salary;
            self::assignActivePaygradeToEmployee(
                $employee->id,
                $data['pay_grade_id'],
                [
                    'assigned_by' => Auth::user()->id,
                    'effective_from' => now(),
                    'base_salary_override' => $newSalaryOverride,
                ]
            );
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }
        return $employee;
    }


    /**
     * Summary of getEmployeeById
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Collection<int, Employee>
     */
    public static function getEmployeeById($id): Employee
    {
        // Find the employee by ID
        $employee = Employee::with(['pay_grades',
         'attachments', 'payrolls', 'currentStatus', 'statusHistories'])
            ->findOrFail($id);
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
        DB::beginTransaction();
        try {
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
            $PayGrade = PayGrade::find($data['pay_grade_id']);
            $newSalaryOverride = $data['base_salary_override'] ? $data['base_salary_override'] : $PayGrade->base_salary;
            $employee->update($data);
            $employee->recordEvent('update', $data);

            if (isset($data['pay_grade_id'])) {
                self::assignActivePaygradeToEmployee(
                    $employee->id,
                    $data['pay_grade_id'],
                    [
                        'assigned_by' => Auth::user()->id,
                        'effective_from' => $data['effective_from'] ?? now(),
                        'base_salary_override' => $newSalaryOverride,
                    ]
                );
            }
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
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

    /**
     * Summary of getNamesFromFullName
     *
     * @param mixed $fullName
     * @return array{first_name: string, last_name: string, middle_name: string}
     */
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
