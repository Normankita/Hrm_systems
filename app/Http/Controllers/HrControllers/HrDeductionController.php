<?php

namespace App\Http\Controllers\HrControllers;

use App\Http\Controllers\Controller;
use App\Models\Deduction;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Services\DeductionService;

class HrDeductionController extends Controller
{
    // List deductions for a given employee
    public function index(Employee $employee)
    {
        $deductions = $employee->deductions()->latest()->paginate(20);
        return view('hr.deductions.index', compact('employee', 'deductions'));
    }

    // Show form to create deduction for a given employee
    public function create(Employee $employee)
    {
        return view('hr.deductions.create', compact('employee'));
    }

    // Store a deduction for the given employee
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'installments' => 'required|integer|min:1',
            'installment_amount' => 'required|numeric|min:0',
        ]);

        $validated['employee_id'] = $employee->id;

        DeductionService::createDeduction($validated);

        return redirect()->route('hr.deductions.index', $employee->id)
            ->with('message', 'Deduction created successfully.');
    }

    // Show a specific deduction (employee context optional if deduction has employee relation)
    public function show(Employee $employee, Deduction $deduction)
    {
        // Optional: confirm $deduction->employee_id === $employee->id for safety
        return view('hr.deductions.show', compact('employee', 'deduction'));
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
            'amount' => 'required|numeric|min:0',
            'installments' => 'required|integer|min:1',
            'installment_amount' => 'required|numeric|min:0',
        ]);

        // Ensure employee_id is consistent with the URL employee
        $validated['employee_id'] = $employee->id;

        DeductionService::updateDeduction($deduction, $validated);

        return redirect()->route('hr.deductions.index', $employee->id)
            ->with('message', 'Deduction updated successfully.');
    }

    // Delete a deduction for the given employee
    public function destroy(Employee $employee, Deduction $deduction)
    {
        DeductionService::deleteDeduction($deduction);

        return redirect()->route('hr.deductions.index', $employee->id)
            ->with('message', 'Deduction deleted successfully.');
    }
}
