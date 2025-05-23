<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\DeductionService;
use App\Models\Deduction;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeDeductionController extends Controller

{
    public function __construct(private DeductionService $DeductionService)
    {
    }
    // List deductions for a given employee
    public function index(Employee $employee)
    {
        $deductions = $employee->deductions()->latest()->paginate(20);
        return view('employee.manage.employee.deductions', compact('employee', 'deductions'));
    }

    // Show form to create deduction for a given employee
    public function create(Employee $employee)
    {
        return view('hr.deductions.create', compact('employee'));
    }

    // Store a deduction for the given employee
public function store(Request $request, Employee $employee)
{
    // Remove this after verifying your inputs
    // dd($request->all());

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
    public function show(Employee $employee)
    {
        return view('employee.manage.employee.deductions', compact('employee'));
    }

    // Show form to edit deduction for a given employee
    public function edit(Employee $employee, Deduction $deduction)
    {
        return view('hr.deductions.edit', compact('employee', 'deduction'));
    }

    // Update a deduction for the given employee
    public function update(Request $request, Employee $employee, Deduction $deduction)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'installments' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

    $validated['installment_amount'] = $validated['total_amount'] / $validated['installments'];


        // Ensure employee_id is consistent with the URL employee
        $validated['employee_id'] = $employee->id;

        DeductionService::updateDeduction($deduction, $validated);

        return redirect()->back()->with('success', 'Deduction updated successfully.');
    }

    // Delete a deduction for the given employee
    public function destroy(Employee $employee, Deduction $deduction)
    {
        DeductionService::deleteDeduction($deduction);

        return redirect()->back()
            ->with('success', 'Deduction deleted successfully.');
    }
}
