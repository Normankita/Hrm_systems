<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Utils\Traits\EmployeeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Log;
use Spatie\Permission\Models\Role;

class AdminEmployeeController extends Controller
{
    use EmployeeTrait;

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

    public function store(StoreEmployeeRequest $request)
    {
        $attachments = [];

        // Handle passport photo
        if ($request->hasFile('passport_photo') && $request->file('passport_photo')->isValid()) {
            $photo = $request->file('passport_photo');
            $filename = 'passport_' . time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('attachments/employees/profile_photos', $filename, 'public');
            $request->merge(['profile_picture' => $filename]);

            $attachments[] = [
                'filename' => $filename,
                'path' => $path,
                'type' => 'passport_photo',
            ];

            // Log the entire attachments array
            Log::info('Attachments array:', $attachments);

       
        }

        $request->merge([
            'company_id' => Auth::user()->company_id,
            'full_name' => $request->input('first_name') . ' ' . $request->input('last_name'),
        ]);

        $employee = $this->createEmployee($request->all());

        $this->handleDocumentUpload($request, $attachments, 'tin_document', 'TIN', $employee);
        $this->handleDocumentUpload($request, $attachments, 'national_id_document', 'National ID', $employee);
        $this->handleDocumentUpload($request, $attachments, 'cv_document', 'CV', $employee);

        // Certificates - multiple
        if ($request->hasFile('certificates')) {
            foreach ($request->file('certificates') as $index => $certificate) {
                if ($certificate && $certificate->isValid()) {
                    $filename = 'certificate_' . $index . '_' . time() . '.' . $certificate->getClientOriginalExtension();
                    $path = $certificate->storeAs('attachments/employees', $filename, 'public');

                    $attachments[] = [
                        'filename' => $filename,
                        'path' => $path,
                        'type' => 'certificate',
                    ];
                }
            }
        }

        foreach ($attachments as $attachment) {
            $employee->attachments()->create($attachment);
        }

        return redirect()->route('admin.employees.show', $employee->id)
            ->with('success', 'Employee created successfully');
    }

    private function handleDocumentUpload(Request $request, array &$attachments, string $fieldName, string $label, $employee)
    {
        if ($request->hasFile($fieldName) && $request->file($fieldName)->isValid()) {
            $type = strtolower(str_replace(' ', '_', $label));

            \Log::info('Generated type fromnorman: ' . $type);

            // Remove old document if exists
            $old = $employee->attachments()->where('attachmentable_type', 'App\Models\Employee')
                ->where('attachmentable_id', $employee->id)
                ->where('type', $type)
                ->first();

            if ($old) {
                \Log::info('Deleting old attachment: ' . $old->path);
                Storage::disk('public')->delete($old->path);
                $old->delete();
            } else {
                \Log::warning('No old attachment found for type: ' . $type);
            }

            $file = $request->file($fieldName);
            $filename = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('attachments/employees', $filename, 'public');

            $attachments[] = [
                'filename' => $filename,
                'path' => $path,
                'type' => $type,
            ];
        }
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

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $request->merge([
            'company_id' => Auth::user()->company_id,
            'full_name' => $request->input('first_name') . ' ' . $request->input('last_name'),
        ]);

        $employee = EmployeeTrait::getEmployeeById($id);
        $employee->update($request->all());

        $attachments = [];

        // Handle passport photo
        if ($request->hasFile('passport_photo') && $request->file('passport_photo')->isValid()) {
            $old = $employee->attachments()->where('attachmentable_type', 'App\Models\Employee')
                ->where('attachmentable_id', $employee->id)
                ->where('type', 'passport_photo') // Assuming 'passport_photo' is in 'type'
                ->first();
            if ($old) {
                Log::info('Deleting old attachment: ' . $old->path);
                Storage::disk('public')->delete($old->path);
                $old->delete();
            } else {
                Log::warning('No old attachment found for type: ' . 'passport_photo');
            }

            $photo = $request->file('passport_photo');
            $filename = 'passport_' . time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('attachments/employees/profile_photos', $filename, 'public');
            $employee->update(['profile_picture' => $filename]);

            $attachments[] = [
                'filename' => $filename,
                'path' => $path,
                'type' => 'passport_photo',
            ];

            // Log the entire attachments array
            Log::info('Attachments array:', $attachments);
           
        }

        // Replace existing single-file attachments
        $this->handleDocumentUpload($request, $attachments, 'tin_document', 'TIN', $employee);
        $this->handleDocumentUpload($request, $attachments, 'national_id_document', 'National ID', $employee);
        $this->handleDocumentUpload($request, $attachments, 'cv_document', 'CV', $employee);

        // Replace all old certificates if new ones uploaded
        if ($request->hasFile('certificates')) {
            $oldCerts = $employee->attachments()->where('type', 'certificate')->get();
            foreach ($oldCerts as $cert) {
                \Log::info('Deleting old attachment: ' . $cert->path);
                Storage::disk('public')->delete($cert->path);
                $cert->delete();
            }

            foreach ($request->file('certificates') as $index => $certificate) {
                if ($certificate && $certificate->isValid()) {
                    $filename = 'certificate_' . $index . '_' . time() . '.' . $certificate->getClientOriginalExtension();
                    $path = $certificate->storeAs('attachments/employees', $filename, 'public');

                    $attachments[] = [
                        'filename' => $filename,
                        'path' => $path,
                        'type' => 'certificate',
                    ];
                }
            }
        }

        foreach ($attachments as $attachment) {
            $employee->attachments()->create($attachment);
        }

        return redirect()->route('admin.employees.show', $employee->id)
            ->with('success', 'Employee updated successfully');
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
            'password' => bcrypt($request->password),
        ]);

        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Password updated successfully'
        ]);
    }
}
