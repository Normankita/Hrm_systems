<?php


namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeLeavesController extends Controller
{
    public function index()
    {
        dd("index to me");
        $employees = Employee::with('leaves')
            ->whereHas('leaves')
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('employee.leaves.index')
            ->with('employees', $employees);
    }


    public function show($leave)
    {
        dd($leave);
        $leave = Leave::find($leave);

        return view('employee.leaves.show')
            ->with('leave', $leave);
    }


    public function inspect(Request $request, Leave $leave) {
        $status = $request->input('status');
        $comment = $request->input('comment');

        $status = $status == 0 ? "rejected" : 'approved';

        LeaveApproval::create([
            'employee_id' => $leave->employee_id,
            'leave_id' => $leave->id,
            'inspector_id' => Auth::user()->id,
            'status' => $status,
            'comment' => $comment,
            'inspected_at' => now()
        ]);
        $leave->update(['status' =>$status]);
        return redirect()->back()
            ->with(['status' => 'success', 'message' => 'operation was a successfull']);
    }

}
