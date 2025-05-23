<?php
namespace App\Http\Controllers\EmployeeControllers;


use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\PayGradeTrait;
use App\Models\PayGrade;
use Illuminate\Http\Request;


class EmployeeManagePayrollPayGradeController extends Controller
{
    use PayGradeTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pay_grades = PayGrade::orderBy("created_at", "desc")->paginate(10);
        return view('payroll.paygrade.index', compact('pay_grades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validatePayGrade($request);
        $this->createPayGrade($request);

        return redirect()->route('payroll.paygrades.index')->with('success', 'Pay Grade created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(PayGrade $payGrade)
    {
        // Assuming you will later show the view
        return view('payroll.paygrade.show', compact('payGrade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PayGrade $payGrade)
    {
        return view('payroll.paygrade.edit', compact('payGrade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PayGrade $payGrade)
    {
        $this->validatePayGrade($request, $payGrade->id);
        $this->updatePayGrade($request, $payGrade);

        return redirect()->route('payroll.paygrades.index')->with('success', 'Pay Grade updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PayGrade $payGrade)
    {
        $payGrade->delete();
        return redirect()->route('payroll.paygrades.index')->with('success', 'Pay Grade deleted successfully');
    }
}
