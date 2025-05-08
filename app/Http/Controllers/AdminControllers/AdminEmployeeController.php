<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Services\EmployeeService;
use App\Http\Utils\Traits\EmployeeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Http\Utils\Traits\UploadFileTrait;
use Illuminate\Support\Facades\Hash;

class AdminEmployeeController extends Controller
{
    use EmployeeTrait, UploadFileTrait;


    public function __construct(private EmployeeService $employeeService){}

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $employees = Auth::user()->company->employees()
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.employee.index', compact('employees'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'ADMIN')->get();
        return view('admin.employee.create', compact('roles'));
    }

    /**
     * Store a new employee along with attachments.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $outcome = $this->employeeService->storeEmployee(
            $request, self::ATTACHMENT_TYPES,
            );
        return redirect()->route('admin.employees.show', $outcome['employee']->id)
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
        $attachments = $employee->attachments()->get();

        return view('admin.employee.show', compact('employee', 'attachments'));
    }


    /**
     * Summary of edit
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $employee = self::getEmployeeById($id);
        $names = $this->getNamesFromFullName($employee->full_name);
        $employee->first_name = $names['first_name'];
        $employee->last_name = $names['last_name'];
        $roles = Role::where('name', '!=', 'ADMIN')->get();

        return view('admin.employee.edit', compact('employee', 'roles'));
    }


    /**
     * Update an existing employee record and manage file replacement.
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $outcome = $this->employeeService->updateEmployee($request, $id);
        return redirect()->route('admin.employees.show',
             $outcome['employee']->id)
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
     * Summary of updatePassportPhoto
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassportPhoto(Request $request, $id)
    {
        $outcome= $this->employeeService->updateProfilePhoto($request, $id);
      
        if($outcome){
            return redirect()->route('admin.employees.show', parameters: $outcome['employee']->id)
                ->with('success', 'Passport photo updated successfully');
        }
        return redirect()->back()->with([
            'status' => 'error',
            'message' => 'Invalid passport photo upload'
        ]);
    }
}
