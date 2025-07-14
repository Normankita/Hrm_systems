<?php
namespace App\Http\Utils\Traits;

use App\Models\Employee;
use Exception;
use Illuminate\Support\Facades\DB;

trait AllowanceTrait
{
    /**
     * Accepts allowance data and creates allowance for an employee
     * @param mixed $employeeId
     * @param mixed $data
     * @param mixed $allowance_id
     * @return Employee|\Illuminate\Database\Eloquent\Collection<int, Employee>
     */
    public static function createAllowanceForEmployee($employeeId, $data = [], $allowance_id, $user = null)
    {
        $employee = Employee::findOrFail($employeeId);
        $companyId = $employee->company_id;
        DB::beginTransaction();
        try {
            $employee->allowances()->attach($allowance_id, [
                'amount' => $data['amount'],
                'allowance_frequency_id' => $data['frequency_id'],
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            dd($user);
            $employee->recordEvent('add', $data, $user['id'], $companyId);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e; // Re-throw the exception to handle it outside
        }
        return $employee;
    }
}
