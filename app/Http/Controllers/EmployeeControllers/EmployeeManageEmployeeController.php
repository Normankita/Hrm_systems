<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Services\EmployeeService;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Http\Utils\Traits\UploadFileTrait;
use App\Models\PayGrade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class EmployeeManageEmployeeController extends Controller
{
    use EmployeeTrait, UploadFileTrait;

    public function __construct(private EmployeeService $employeeService)
    {
    }


    public function index()
    {
        $employees = Auth::user()->company->employees()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.manage.employee.index', compact('employees'));
    }


    public function create()
    {
        $roles = Role::where('name', '!=', 'ADMIN')->get();
        $pay_grades = PayGrade::get();
        return view('employee.manage.employee.create', compact('roles', 'pay_grades'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $outcome = $this->employeeService->storeEmployee(
            $request,
            self::ATTACHMENT_TYPES,
        );

        return redirect()->route('employee.manage.employees.show', $outcome['employee']->id)
            ->with('success', 'Employee created successfully');
    }

    public function edit($id)
    {
        $employee = self::getEmployeeById($id);
        $names = $this->getNamesFromFullName($employee->full_name);
        $employee->first_name = $names['first_name'];
        $employee->middle_name = $names['middle_name'];
        $employee->last_name = $names['last_name'];
        $roles = Role::where('name', '!=', 'ADMIN')->get();
        $pay_grades = PayGrade::get();

        return view('employee.manage.employee.edit', compact('employee', 'roles', 'pay_grades'));
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {

        $outcome = $this->employeeService->updateEmployee($request, $id);

        return redirect()->route('employee.manage.employees.show', $outcome['employee']->id)
            ->with('success', 'Employee updated successfully');
    }

    public function show($id)
    {
        $employee = EmployeeTrait::getEmployeeById($id);
        $attachments = $employee->attachments()->get();
        $deductions = $employee->deductions()->get();

        return view('employee.manage.employee.show', compact('employee', 'attachments'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        $employee = EmployeeTrait::getEmployeeById($id);

        if (!$employee || !$employee->user) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'User not found for the selected employee'
            ]);
        }

        $employee->user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Password updated successfully'
        ]);
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassportPhoto(Request $request, $id)
    {
        $outcome = $this->employeeService->updateProfilePhoto($request, $id);
        if ($outcome) {
            return redirect()->route('employee.manage.employees.show', $outcome['employee']->id)
                ->with('success', 'Passport photo updated successfully');
        }

        return redirect()->back()->with([
            'status' => 'error',
            'message' => 'Invalid passport photo upload'
        ]);
    }
}
