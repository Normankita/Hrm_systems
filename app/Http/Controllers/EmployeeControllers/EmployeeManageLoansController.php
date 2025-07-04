<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Loan;
use Illuminate\Http\Request;

class EmployeeManageLoansController extends Controller
{
    public function index()
    {
        $loans = Loan::with('employee')->latest()->get();
        return view('employee.manage.loans.index', compact('loans'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('loans.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:1000',
            'months_to_pay' => 'required|integer|min:1',
            'issued_date' => 'required|date',
        ]);

        $amount = $request->amount;
        $total = $amount;

        Loan::create([
            'employee_id' => $request->employee_id,
            'amount' => $amount,
            'months_to_pay' => $request->months_to_pay,
            'issued_date' => $request->issued_date,
            'total_payable' => $total,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('loans.index')->with('success', 'Loan issued successfully');
    }
}
