<?php


namespace App\Http\Controllers\HrControllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveApproval;
use Auth;
use Illuminate\Http\Request;

class HrLeavesController extends Controller
{
    public function index()
    {
        $employees = Employee::with('leaves')
            ->whereHas('leaves')
            ->paginate(20);
        return view('hr.leaves.index')
            ->with('employees', $employees);
    }


    public function show($leave)
    {
        $leave = Leave::find($leave);
        return view('hr.leaves.show')
            ->with('leave', $leave);
    }


    private function deductStatus($passed) {
        switch ($passed) {
            case 'approved':
                
                break;
            
            default:
                # code...
                break;
        }
    }


    public function inspect(Request $request, Leave $leave) {
        $status = $request->input('status');
        $comment = $request->input('comment');

        LeaveApproval::create([
            'employee_id' => $leave->employee_id,
            'leave_id' => $leave->id,
            'inspector_id' => Auth::user()->id,
            'status' => 'approved',
            'comment' => $comment,
            'inspected_at' => now()
        ]);
        $leave->update(['status' => $status]);
        return redirect()->back()
            ->with(['status' => 'success', 'message' => 'operation was a successfull']);
    }

}
