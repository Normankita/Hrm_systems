<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeManageLeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::all()->sortByDesc('created_at');
        return view('employee.manage.leave_type.index')
            ->with('leaveTypes', $leaveTypes);
    }


    public function store(Request $request)
    {
        $isCompensated = $request->input('is_compensated') ?? '0';
        $isDeducted = $request->input('deducts_from_annual_leave');
        // convert to boolean
        $isCompensated = $isCompensated == '1' ? true : false;
        $isDeducted = $isDeducted == '1' ? true : false;

        $rules = [
            'name' => 'required|string|max:255|unique:leave_types,name',
            'description' => 'nullable|string|max:255',
            'deducts_from_annual_leave' => 'boolean',
        ];
        $request->request->add(
            ['code' => str_replace(' ', '_',
                $request->name)]
        );
        Validator::make($request->all(), $rules)->validate();
       $leaveType= LeaveType::create($request->all());
       $leaveType->recordEvent('add', $request->all());
        return redirect()->back()
            ->with('success', 'Leave Type created successfully');
    }


    public function update(Request $request, LeaveType $leaveType)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'deducts_from_annual_leave' => 'boolean',
        ];

        Validator::make($request->all(), $rules)->validate();
        $data= [
            'name' => $request->name,
            'description' => $request->description,
            'is_compensated' => $request->is_compensated,
            'deducts_from_annual_leave' => $request->deducts_from_annual_leave,
        ];
        $leaveType->update($data);
       $leaveType->recordEvent('update', $data);
        return redirect()->back()
            ->with(
                'success',
                'Leave Type updated successfully'
            );
    }


    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();
        return redirect()->back()
            ->with('success', 'Leave Type deleted successfully');
    }

}
