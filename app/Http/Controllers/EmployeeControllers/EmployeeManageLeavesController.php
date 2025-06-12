<?php


namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeManageLeavesController extends Controller
{
    public function index()
    {
        $employees = Employee::with('leaves')
            ->whereHas('leaves')
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('employee.manage.leaves.index')
            ->with('employees', $employees);
    }


    public function show($leave)
    {
        $leave = Leave::find($leave);

        return view('employee.manage.leaves.show')
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
        $leave->update(['status' =>$status, 'comment'=>$comment]);
        return redirect()->back()
            ->with(['status' => 'success', 'message' => 'operation was a successfull']);
    }



    /**
     * get the rejected leaves
     * @return \Illuminate\Contracts\View\View
     */
    public function getRejectedLeavesPage()
    {
        $rejectedLeaves = Leave::where('status', 'rejected')
            ->get();
        return view('employee.manage.leaves.reports.rejected')
            ->with('rejectedLeaves', $rejectedLeaves);
    }

    
    /**
     * getting leaves
     * @return \Illuminate\Contracts\View\View
     */
    public function getAcceptedLeavesPage()
    {
        $acceptedLeaves = Leave::where('status', 'rejected')
            ->get();
        return view('employee.manage.leaves.reports.accepted')
            ->with('acceptedLeaves', $acceptedLeaves);
    }


    /**
     * getting the leaves that are pending
     * @return \Illuminate\Contracts\View\View
     */
    public function getPendingLeavesPage() {
        $pendingLeaves = Leave::where('status', 'pending')
            ->filterByDate(request())
            ->get();
        return view('employee.manage.leaves.reports.pending')
            ->with('pendingLeaves', $pendingLeaves);
    }

}
