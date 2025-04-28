<?php


namespace App\Http\Controllers\HrControllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;

class HrLeavesController extends Controller
{
    public function index()
    {
        $leaves = Employee::with('leaves')
            ->whereHas('leaves')
            ->paginate(20);
        return view('hr.leaves.index')
            ->with('leaves', $leaves);
    }


    public function show($leave)
    {
        $leave = Leave::find($leave);
        return view('hr.leaves.show')
            ->with('leave', $leave);
    }
}
