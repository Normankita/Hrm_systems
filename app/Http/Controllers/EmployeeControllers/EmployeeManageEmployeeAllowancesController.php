<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\utils\Traits\AllowanceTrait;
use App\Models\Allowance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeManageEmployeeAllowancesController extends Controller
{
    use AllowanceTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Employee $employee)
    {
        // Eager load 'allowances' relationship
        $employee = Employee::with('allowances')->findOrFail($employee->id);
        $allowances = Allowance::all();

        return view("employee.manage.employee.allowances", compact("employee", "allowances"));
    }


    public function store(Request $request, $employeeId)
    {
        $request->validate([
            'allowance_id' => ['required', 'exists:allowances,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'frequency' => ['required', Rule::in(['weekly', 'monthly', 'yearly', 'one-time'])],
            'effective_from' => ['nullable', 'date'],
            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],
        ]);
        $employee=$this->createAllowanceForEmployee($employeeId, $request);
        $employee->recordEvent('add', ['amount'=>$request->input('amount'), 'frequency_id'=>$request->input('frequency_id')]);
        return back()->with('success', 'Allowance added.');
    }



    public function toggleStatus(Request $request, $employeeId, $allowanceId)
    {
        $employee = Employee::findOrFail($employeeId);
        $allowance = $employee->allowances()->where('allowance_id', $allowanceId)->firstOrFail();
        $employee->allowances()->updateExistingPivot(
            $allowanceId,
            [
                'status' => $request->input('status') ? true : false,
            ]
        );
        $employee->recordEvent('update', $request->all());
        return redirect()->back()->with('success', 'Allowance status updated successfully.');
    }




    public function update(Request $request, $employeeId, $allowanceId)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'frequency' => ['required', Rule::in(['monthly', 'quarterly', 'yearly', 'one-time'])],
        ]);

        $employee = Employee::findOrFail($employeeId);

        $employee->allowances()->updateExistingPivot($allowanceId, [
            'amount' => $request->amount,
            'frequency' => $request->frequency,
            'updated_at' => now(),
        ]);
        $employee->recordEvent('update', $request->all());
        return back()->with('success', 'Allowance updated.');
    }

    public function destroy($employeeId, $allowanceId)
    {
        $employee = Employee::findOrFail($employeeId);
        $employee->recordEvent('delete', $employee->allowances()->find($allowanceId)->toArray());
        $employee->allowances()->detach($allowanceId);
        return back()->with('success', 'Allowance deleted.');
    }
}
