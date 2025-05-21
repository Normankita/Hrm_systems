<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Services\EmployeeService;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Http\Utils\Traits\UploadFileTrait;
use App\Models\Employee;
use App\Models\PayGrade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminEmployeeController extends Controller
{
    use EmployeeTrait, UploadFileTrait;

    public function __construct(private EmployeeService $employeeService) {}

    public function index(): View
    {
        $employees = Auth::user()->company->employees()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.employee.index', compact('employees'));
    }

    public function permissionsAll(): View
    {
        $employees = Auth::user()->company->employees()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.employee.employee-list', compact('employees'));
    }

    public function editPermissions(int $id): View
    {
        $employee = Employee::findOrFail($id);
        $permissions = Permission::all();

        return view('admin.employee.manage_user_permissions', compact('employee', 'permissions'));
    }

    public function create(): View
    {
        $roles = Role::where('name', '!=', 'ADMIN')->get();
        $pay_grades = PayGrade::all();

        return view('admin.employee.create', compact('roles', 'pay_grades'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $outcome = $this->employeeService->storeEmployee(
            $request,
            self::ATTACHMENT_TYPES
        );

        return redirect()
            ->route('admin.employees.show', $outcome['employee']->id)
            ->with('success', 'Employee created successfully');
    }

    public function show($id): View
    {
        $employee = $this->getEmployeeById($id);
        $attachments = $employee->attachments()->get();

        return view('admin.employee.show', compact('employee', 'attachments'));
    }

    public function edit($id): View
    {
        $employee = $this->getEmployeeById($id);
        $names = $this->getNamesFromFullName($employee->full_name);

        $employee->first_name = $names['first_name'];
        $employee->middle_name = $names['middle_name'];
        $employee->last_name = $names['last_name'];

        $roles = Role::where('name', '!=', 'ADMIN')->get();
        $pay_grades = PayGrade::all();

        return view('admin.employee.edit', compact('employee', 'roles', 'pay_grades'));
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $outcome = $this->employeeService->updateEmployee($request, $id);

        return redirect()
            ->route('admin.employees.show', $outcome['employee']->id)
            ->with('success', 'Employee updated successfully');
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        $employee = $this->getEmployeeById($id);

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

    public function updatePassportPhoto(Request $request, $id)
    {
        $outcome = $this->employeeService->updateProfilePhoto($request, $id);

        if ($outcome) {
            return redirect()
                ->route('admin.employees.show', $outcome['employee']->id)
                ->with('success', 'Passport photo updated successfully');
        }

        return redirect()->back()->with([
            'status' => 'error',
            'message' => 'Invalid passport photo upload'
        ]);
    }
}
