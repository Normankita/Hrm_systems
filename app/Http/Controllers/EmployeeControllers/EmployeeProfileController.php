<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Services\EmployeeService;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Http\Utils\Traits\UploadFileTrait;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmployeeProfileController extends Controller
{

    use UploadFileTrait;

    
    public function __construct(private EmployeeService $employeeService){}

    public function index()
    {
        $employee = Auth::user()->employee;

        $attachments = $employee->attachments()->get();
        return view('employee.profile.index', compact('employee', 'attachments'));
    }

    public function edit($id)
    {
        // $employee = EmployeeTrait::getEmployeeById($id);
        $employee = Employee::find($id);

        $attachments = $employee->attachments()->get();
        // // Split full name
        $fullName = $employee->full_name;
        $nameParts = explode(' ', $fullName, 2);
        // Only split into 2 parts: first and last
        $employee->first_name = $nameParts[0];
        $employee->last_name = $nameParts[1] ?? '';
        return view('employee.profile.edit',
        compact('employee', 'attachments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
{
    $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'gender' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'phone_number' => 'nullable|string|max:20',
        'national_id' => 'nullable|string|max:50',
        'marital_status' => 'nullable|string|max:50',
        'residential_address' => 'nullable|string|max:255',
        'tin_number' => 'nullable|string|max:50',
    ];

    $validate = Validator::make($request->all(), $rules);

    if ($validate->fails()) {
        return redirect()->back()
            ->withErrors($validate)
            ->withInput();
    }

    $data = $request->all();
    $data['full_name'] = $request->input('first_name') . ' ' . $request->input('last_name');

    EmployeeTrait::updateEmployee($employee->id, $data);

    return redirect()->route('employees.profile.index')
        ->with('success', 'Employee updated successfully');
}

public function editPassword($id)
{
    $employee = EmployeeTrait::getEmployeeById($id);
    if ($employee->user->id !== Auth::id()){
        abort(403);
    }

    
    return view('employee.profile.edit_password', compact('employee'));
}

public function updatePassword(Request $request, Employee $employee)
{
    
    if ($employee->user->id !== Auth::id()) {
        abort(403);
    }

    $validated = $request->validate([
        'current_password' => ['required'],
        'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    if (!Hash::check($validated['current_password'], $employee->user->password)) {
        return back()->withErrors(['current_password' => 'The current password you entered is incorrect.'])->withInput();
    }

    $employee->user->update([
        'password' => Hash::make($validated['new_password']),
        'is_default_configs' => 0,
    ]);

    return redirect()->route('employees.profile.index')->with('success', 'Password updated successfully.');
}

public function updatePassportPhoto(Request $request, $id)
{
    $outcome= $this->employeeService->updateProfilePhoto($request, $id);
      
    if($outcome){
            return redirect()->route('employees.profile.index', $outcome['employee']->id)
               ->with('success', __('Passport photo updated successfully'));
       }

   return redirect()->back()->withErrors([
       'message' => ('Invalid passport photo upload'),
       'status'=>'error',
   ]);
}
  

}
