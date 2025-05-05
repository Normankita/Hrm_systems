<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Utils\Traits\EmployeeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AdminEmployeeController extends Controller
{
    use EmployeeTrait;

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $employees = Auth::user()->company->employees()
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($employees);
        return view('admin.employee.index')
            ->with('employees', $employees);
    }



    /**
     * Summary of create
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'ADMIN')->get();
        return view('admin.employee.create')
            ->with('roles', $roles);
    }



    /**
     * Summary of store
     * @param \App\Http\Requests\StoreEmployeeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreEmployeeRequest $request)
    {
        
        // uploading files that are comming from the request

        if ($request->hasFile('passport_photo')) {
            $file = $request->file('passport_photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $request->merge(['profile_picture' => $filename]);
        }

        // adding company_id and department_id to the request
        $request->merge([
            'company_id' => Auth::user()->company_id,
            'department_id' => $request->input('department_id'),
            'full_name' => $request->input('first_name') . ' ' . $request->input('last_name'),

        ]);
        $employee = EmployeeTrait::createEmployee($request->all());
        return redirect()->route('admin.employees.show', $employee->id)
            ->with('success', 'Employee created successfully');
    }




    /**
     * Summary of show
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $employee = EmployeeTrait::getEmployeeById($id);
        return view('admin.employee.show', ['employee' => $employee]);
    }


    /**
     * Summary of edit
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $employee = self::getEmployeeById($id);
        // Split full name into first and last name
        $names = $this->getNamesFromFullName($employee->full_name);
        $employee->first_name = $names['first_name'];
        $employee->last_name = $names['last_name'];
        $roles = Role::where('name', '!=', 'ADMIN')->get();

        return view('admin.employee.edit', compact(
            'employee', 'roles'));
    }



    /**
     * Summary of update
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        // adding company_id and department_id to the request
        $request->merge([
            'company_id' => Auth::user()->company_id,
            'department_id' => $request->input('department_id'),
            'full_name' => $request->input('first_name') . ' ' . $request->input('last_name'),

        ]);
        $employee = EmployeeTrait::getEmployeeById($id);
        $employee->update($request->all());

        return redirect()->route('admin.employees.show', $employee->id)
            ->with('success', 'Employee updated successfully');
    }

/**
 * Summary of updatePassword
 * @param \Illuminate\Http\Request $request
 * @param mixed $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function updatePassword(Request $request, $id)
{
    $request->validate([
        'password' => ['required', 'string', 'min:8'], // Add more rules if needed
    ]);

    $employee = EmployeeTrait::getEmployeeById($id);

    // Double check user record exists
    if (!$employee || !$employee->user) {
        return redirect()->back()
            ->with(['status' => 'error', 'message' => 'User not found for the selected employee']);
    }

    $employee->user->update([
        'password' => bcrypt($request->password),
    ]);

    return redirect()->back()
        ->with(['status' => 'success', 'message' => 'Password updated successfully']);
}


}
