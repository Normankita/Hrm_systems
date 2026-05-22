<?php
namespace App\Http\Utils\Traits;

use App\Models\Employee;
use App\Models\EmployeeAllowance;
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
            $details = [
                'amount' => $data['amount'],
                'allowance_frequency_id' => $data['frequency_id'],
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $employee->allowances()->attach($allowance_id, $details);
            // Then query the pivot table
            $pivot = EmployeeAllowance::where('employee_id', $employee->id)
                ->where('allowance_id', $allowance_id)
                ->latest('id') // Assuming pivot table has an `id` column
                ->first();
            if (!$pivot) {
                throw new Exception('Failed to create allowance for employee.');
            }
            $pivot->recordEvent(
                'add',
                $details,
                $user['id'],
                $companyId
            );
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e; // Re-throw the exception to handle it outside
        }
        return $employee;
    }
}
