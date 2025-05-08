<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Utils\Traits\EmployeeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Http\Utils\Traits\UploadFileTrait;

class AdminEmployeeController extends Controller
{
    use EmployeeTrait, UploadFileTrait;

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
        $outcome = $this->storeEmployee($request, self::ATTACHMENT_TYPES,
            fn($file, $value, &$attachments) =>   $this->handleDocumentUpload($file, $value, $attachments));
        return redirect()->route('admin.employees.show', $outcome['employee']->id)
            ->with('success', 'Employee created successfully');
    }


    public function show($id)
    {
        $employee = EmployeeTrait::getEmployeeById($id);
        $attachments = $employee->attachments()->get();

        return view('admin.employee.show', compact('employee', 'attachments'));
    }

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
        $request->merge([
            'company_id' => Auth::user()->company_id,
            'full_name' => $request->input('first_name') . ' ' . $request->input('last_name'),
        ]);

        $employee = EmployeeTrait::getEmployeeById($id);
        $employee->update($request->all());

        $attachments = [];

        foreach ([ 'certificates' , ...self::ATTACHMENT_TYPES] as $key => $value) {
            $file = $request->file($key);
            $this->handleDocumentUpload(
                $file,
                $value,
                $attachments
            );
            // Delete the old document of this type if it exists.
            $this->deleteOldAttachment($employee, $value);
        }
        // Save all newly uploaded attachments.
        foreach ($attachments as $attachment) {
            $employee->attachments()->create($attachment);
        }

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
            'password' => bcrypt($request->password),
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
        $request->validate([
            'profile_picture' => [
                'required',
                'mimes:jpeg,png,jpg',
            ],
        ]);

        $employee = EmployeeTrait::getEmployeeById($id);
        if (!$employee) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Employee not found'
            ]);
        }

        if (
            $request->hasFile('profile_picture') &&
            $request->file('profile_picture')->isValid()
        ) {
            // Upload the new passport photo.
            $photo = $request->file('profile_picture');
            $filename = 'profile_picture_' . time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('attachments/employees/profile_photos', $filename, 'public');

            // delete the existing profile if it exists.
            $this->deleteFile($employee->profile_picture);

            // Update the employee's profile picture.
            $employee->update(['profile_picture' => $path]);

            return redirect()->route('admin.employees.show', $employee->id)
                ->with('success', 'Passport photo updated successfully');
        }

        return redirect()->back()->with([
            'status' => 'error',
            'message' => 'Invalid passport photo upload'
        ]);
    }
}
