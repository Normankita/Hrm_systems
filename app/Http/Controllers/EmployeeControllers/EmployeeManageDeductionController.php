<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\DeductionService;
use App\Http\Utils\Helpers;
use App\Http\Utils\Traits\DeductionsTrait;
use App\Models\Deduction;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class EmployeeManageDeductionController extends Controller
{
    use DeductionsTrait;

    public function __construct(private DeductionService $DeductionService)
    {
    }
    // List deductions for a given employee
    public function index(Employee $employee)
    {
        $deductions = $employee->deductions()->latest()->paginate(20);
        return view('employee.manage.employee.deductions', compact('employee', 'deductions'));
    }


    // Store a deduction for the given employee
    public function store(Request $request, Employee $employee)
    {
        Helpers::sanitizeRequestNumbers($request);
        
        // Remove this after verifying your inputs
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'installments' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $validated['employee_id'] = $employee->id;
        $validated['installment_amount'] = $validated['total_amount'] / $validated['installments'];

        DeductionService::createDeduction($validated);

        return redirect()->back()->with('success', 'Deduction created successfully.');
    }



    // Show a specific deduction (employee context optional if deduction has employee relation)
    public function show(Employee $employee, Deduction $deduction)
    {
        return view('employee.manage.employee.deductions', compact('employee', 'deduction'));
    }

    // Show form to edit deduction for a given employee
    public function edit(Employee $employee, Deduction $deduction)
    {
        return view('hr.deductions.edit', compact('employee', 'deduction'));
    }

    // Update a deduction for the given employee
    public function update(Request $request, Employee $employee, Deduction $deduction)
    {
        Helpers::sanitizeRequestNumbers($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'installments' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // chack if the entered amount is greater or equal to the paid
        $paidAmount = $this->getPaidAmount($employee);

        if ($validated['total_amount'] < $paidAmount) {
            return redirect()->back()->with(
                'error',
                'Total amount cannot be less than the paid amount.'
            );
        }
        // if greater or equal to the paid amount
        // check if the entered installments number is greater than the installments
        if ($validated['total_amount'] > $paidAmount) {
            // checking the number of installments if is greater that the current installments
            if ($this->getCompletedInstallments($employee) >= $validated['installments']) {
                return redirect()->back()->with(
                    'error',
                    'completed Installments cannot be greater or equal to the installments.'
                );
            }
        } elseif ($validated['total_amount'] == $paidAmount) {
            return redirect()->back()->with(
                'error',
                'No intallments remains'
            );
        }

        // check the difference btn the entered installments and paid installments
        $differenceInInstallments = $validated['installments'] - $this->getCompletedInstallments($employee);
        $differenceInAmount = $validated['total_amount'] - $paidAmount;
        // updating the installment_amount
        $validated['installment_amount'] = $differenceInAmount / $differenceInInstallments;

        DB::beginTransaction();
        try {
            // update the old_installment_amount
            $deduction->update([
                'old_installment_amount' => $deduction->installment_amount
            ]);
            // update the deduction
            $deduction->update($validated);
            // saving the data in our database
            DB::commit();
            return redirect()->back()->with('success', 'Deduction updated successfully.');

        } catch (Throwable $throwable) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating deduction');
        }

    }

    // Delete a deduction for the given employee
    public function destroy(Employee $employee, Deduction $deduction)
    {
        DeductionService::deleteDeduction($deduction);

        return redirect()->back()
            ->with('success', 'Deduction deleted successfully.');
    }
}
