<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\AllowanceTrait;
use App\Models\Allowance;
use App\Models\AllowanceFrequency;
use App\Models\Employee;
use App\Models\GroupCategoryEmployeeAllowance;
use Illuminate\Container\Attributes\Auth;
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
        // select all user individual disbursements
        $individualDisbursements = $employee->disbursedAllowances()
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->get();
        // select all group category disbursements
        $categoryDisbursementsAllocations = GroupCategoryEmployeeAllowance::whereHas(
            'group_employee_pivot',
            function ($query) use ($employee) {
                $query->where('employee_id', $employee->id);
            }
        )
            ->with('disbursements')
            ->orderBy('created_at', 'desc')
            ->get();
        // merge the two disbursements collections
        $disbursements = $individualDisbursements->merge(
            $categoryDisbursementsAllocations->flatMap(function ($allocation) {
                return $allocation->disbursements->map(function ($disbursement) {
                    $disbursement->categoryBased = true; // Add your custom field here
                    return $disbursement;
                });
            })
        );

        // Eager load 'allowances' relationship
        $employee = Employee::with(
            'employeeAllowances.allowance',
            'employeeAllowances.frequency'
        )->findOrFail($employee->id);
        $frequencies = AllowanceFrequency::all();
        $allowances = $employee->absentAllowance();

        return view(
            "employee.manage.employee.allowances",
            compact(
                "employee",
                "allowances",
                "frequencies",
                "disbursements"
            )
        );
    }


    public function store(Request $request, $employeeId)
    {
        $request->validate([
            'allowance_id' => ['required', 'exists:allowances,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'frequency_id' => ['required'],
        ]);
        $this->createAllowanceForEmployee(
            $employeeId,
            $request,
            $request->input('allowance_id'),
            auth()->user()
        );
        return redirect()->back()->with('success', 'Allowance added.');
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
