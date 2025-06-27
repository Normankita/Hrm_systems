<?php
namespace App\Http\Utils\Traits;

use App\Models\Employee;

trait AllowanceTrait
{
    /**
     * Accepts allowance data and creates allowance for an employee
     * @param mixed $employeeId
     * @param mixed $data
     * @param mixed $allowance_id
     * @return Employee|\Illuminate\Database\Eloquent\Collection<int, Employee>
     */
    public static function createAllowanceForEmployee($employeeId, $data=[], $allowance_id)
    {
        $employee = Employee::findOrFail($employeeId);
        $employee->allowances()->attach($allowance_id, [
            'amount' => $data['amount'],
            'allowance_frequency_id' => $data['frequency_id'],
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $employee->recordEvent('add', $data);
        return $employee;
    }
}