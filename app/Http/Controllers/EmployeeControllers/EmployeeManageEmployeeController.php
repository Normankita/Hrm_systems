<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Services\EmployeeService;
use App\Http\Utils\Helpers;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Http\Utils\Traits\UploadFileTrait;
use App\Models\Employee;
use App\Models\PayGrade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
            ->filterByDate(request())
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
        Helpers::sanitizeRequestNumbers($request);

        $outcome = $this->employeeService->storeEmployee(
            $request,
            self::ATTACHMENT_TYPES,
        );
        if ($outcome['status'] === 'fail') {
            return redirect()->back()
                ->with($outcome)
                ->withInput();
        }
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
         Helpers::sanitizeRequestNumbers($request);

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
        $employee->recordEvent('update', ['details'=>'password change', 'target'=> $employee->id]);
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

    public function UpdatePayGrade(Request $request, Employee $employee)
    {
        Helpers::sanitizeRequestNumbers($request);
        // Validate the request input
        $request->validate([
            'pay_grade_id' => 'required|exists:pay_grades,id',
        ]);

        // Update the pay grade
        self::assignActivePaygradeToEmployee(
            $employee->id,
            $request->pay_grade_id,
            [
                'assigned_by' => auth()->id(),
                'effective_from' => $request->effective_from,
                'base_salary_override' => $request->base_salary_override,
            ]
        );

        return back()->with('success', 'Pay grade updated successfully.');
    }

    /**
     * Summary of excelImport
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function excelImport(Request $request)
    {
        $rules = [
            'file' => 'mimes:ods,csv,xlsx|required|max:500',
        ];
        $validate = Validator::make($request->all(), $rules, $messages = [
            'excel.required' => 'Select Excel sheet First....',
            'excel.max' => 'ExcelSheet must not be greater than 500kb',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        $responce = $this->employeeService->importEmployees($request);
        if ($responce['status'] === 'error') {
            return redirect()->back()->with($responce);
        }
        return redirect()->route(route: 'employee.manage.employees.index')
            ->with($responce);
    }

    /**
     * getting the active employees page
     * @return \Illuminate\Contracts\View\View
     */
    public function getActiveEmployeesPage()
    {
        $employees = Employee::whereHas('currentStatus.status', function ($q) {
            $q->where('name', 'Active');
        })->with('currentStatus.status')->get();


        return view('employee.manage.employee.reports.active', compact('employees'));
    }


    /**
     * function to get the suspended employees page
     * @return \Illuminate\Contracts\View\View
     */
    public function getSuspendedEmployeesPage()
    {
        $employees = Employee::whereHas('currentStatus.status', function ($q) {
            $q->where('name', 'Suspended');
        })->with('currentStatus.status')->get();


        return view('employee.manage.employee.reports.suspended', compact('employees'));
    }


    public function getOnLeaveEmployeesPage()
    {
        $employees = Employee::whereHas('currentStatus.status', function ($q) {
            $q->where('name', 'On Leave');
        })->with('currentStatus.status')->get();

        return view('employee.manage.employee.reports.on_leave', compact('employees'));
    }

    public function getResignedEmployeesPage()
    {
        $employees = Employee::whereHas('currentStatus.status', function ($q) {
            $q->where('name', 'resigned');
        })->with('currentStatus.status')->get();

        return view('employee.manage.employee.reports.resigned', compact('employees'));
    }
    public function getTerminatedEmployeesPage()
    {
        $employees = Employee::whereHas('currentStatus.status', function ($q) {
            $q->where('name', 'Terminated');
        })->with('currentStatus.status')->get();

        return view('employee.manage.employee.reports.terminated', compact('employees'));
    }

    public function updateStatus(Request $request, Employee $employee)
    {
        $request->validate([
            'status' => 'required|exists:statuses,id',
            'effective_date' => 'nullable|date',
            'reason' => 'nullable|string|max:1000',
        ]);

        // Mark all other status histories inactive
        $employee->statusHistories()->update(['isActive' => false]);

        $data=[
            'status_id' => $request->status,
            'reason' => $request->reason,
            'effective_date' => $request->effective_date ?? now(),
            'assigned_by' => auth()->id(),
            'isActive' => true,
        ];
        // Create new status history
        $employee->statusHistories()->create($data);
        $employee->recordEvent('add', $data);

        return redirect()->back()->with('success', 'Employee status updated successfully.');
    }

}
