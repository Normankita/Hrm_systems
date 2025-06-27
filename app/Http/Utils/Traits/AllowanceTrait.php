<?php
namespace App\Http\Utils\Traits;

use App\Models\Employee;

trait AllowanceTrait
{
    public static function createAllowanceForEmployee($employeeId, $data=[], $allowance_id)
    {
        $employee = Employee::findOrFail($employeeId);
        $employee->allowances()->attach($allowance_id, [
            'amount' => $data['amount'],
            'frequency_id' => $data['frequency_id'],
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return $employee;
    }
}