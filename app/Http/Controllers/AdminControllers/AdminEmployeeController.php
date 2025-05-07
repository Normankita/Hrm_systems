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

    // Attachment types for consistency
    protected const ATTACHMENT_TYPES = [
        'passport_photo'      => 'passport_photo',
        'tin_document'        => 'tin',
        'national_id_document'=> 'national_id',
        'cv_document'         => 'cv',
        'certificate'         => 'certificate',
    ];

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
        $attachments = [];

        // Handle passport photo upload using our helper method.
        $this->handlePassportPhotoUpload($request, $attachments);

        // Merge additional fields before creating the employee.
        $request->merge([
            'company_id' => Auth::user()->company_id,
            'full_name'  => $request->input('first_name') . ' '.$request->input('middle_name').' ' . $request->input('last_name'),
        ]);

        $employee = $this->createEmployee($request->all());

        // Handle document uploads (TIN, National ID, CV) using our helper.
        $this->handleDocumentUpload($request, $attachments, 'tin_document', self::ATTACHMENT_TYPES['tin_document'], $employee);
        $this->handleDocumentUpload($request, $attachments, 'national_id_document', self::ATTACHMENT_TYPES['national_id_document'], $employee);
        $this->handleDocumentUpload($request, $attachments, 'cv_document', self::ATTACHMENT_TYPES['cv_document'], $employee);

        // Handle multiple certificates.
        if ($request->hasFile('certificates')) {
            foreach ($request->file('certificates') as $index => $certificate) {
                if ($certificate && $certificate->isValid()) {
                    $filename = 'certificate_' . $index . '_' . time() . '.' . $certificate->getClientOriginalExtension();
                    $path = $certificate->storeAs('attachments/employees', $filename, 'public');

                    $attachments[] = [
                        'filename' => $filename,
                        'path'     => $path,
                        'type'     => self::ATTACHMENT_TYPES['certificate'],
                    ];
                }
            }
        }

        // Save all attachments to the employee.
        foreach ($attachments as $attachment) {
            $employee->attachments()->create($attachment);
        }

        return redirect()->route('admin.employees.show', $employee->id)
            ->with('success', 'Employee created successfully');
    }

    /**
     * Helper to delete any old attachment matching a type.
     */
    private function deleteOldAttachment($employee, string $type)
    {
        $old = $employee->attachments()
            ->where('attachmentable_type', 'App\Models\Employee')
            ->where('attachmentable_id', $employee->id)
            ->where('type', $type)
            ->first();

        if ($old) {
            Log::info("Deleting old attachment of type {$type}: " . $old->path);
            Storage::disk('public')->delete($old->path);
            $old->delete();
        } else {
            Log::warning("No old attachment found for type: {$type}");
        }
    }

    /**
     * Helper to handle passport photo upload.
     */
    private function handlePassportPhotoUpload(Request $request, array &$attachments)
    {
        if ($request->hasFile('passport_photo') && $request->file('passport_photo')->isValid()) {
            $photo = $request->file('passport_photo');
            $filename = 'passport_' . time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('attachments/employees/profile_photos', $filename, 'public');

            // Merge file name into request (if later used in creating employee record)
            $request->merge(['profile_picture' => $filename]);

            $attachments[] = [
                'filename' => $filename,
                'path'     => $path,
                'type'     => self::ATTACHMENT_TYPES['passport_photo'],
            ];

            Log::info('Passport photo uploaded; attachment:', $attachments);
        }
    }

    /**
     * Helper to handle document upload for single-file types such as TIN, National ID, CV.
     * 
     * @param Request $request
     * @param array &$attachments Reference to attachments array
     * @param string $fieldName Input field name in the request
     * @param string $type Predefined attachment type
     * @param mixed $employee Employee model instance
     */
    private function handleDocumentUpload(Request $request, array &$attachments, string $fieldName, string $type, $employee)
    {
        if ($request->hasFile($fieldName) && $request->file($fieldName)->isValid()) {
            // Delete the old document of this type if it exists.
            $this->deleteOldAttachment($employee, $type);

            $file = $request->file($fieldName);
            $filename = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('attachments/employees', $filename, 'public');

            $attachments[] = [
                'filename' => $filename,
                'path'     => $path,
                'type'     => $type,
            ];

            Log::info("Uploaded {$fieldName} as type {$type}:", ['filename' => $filename, 'path' => $path]);
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
        $employee->middle_name= $names['middle_name'];
        $employee->last_name  = $names['last_name'];
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
            'full_name'  => $request->input('first_name') . ' '.$request->input('middle_name').' ' . $request->input('last_name'),

        ]);

        $employee = EmployeeTrait::getEmployeeById($id);
        $employee->update($request->all());

        $attachments = [];

        // Handle passport photo update.
        if ($request->hasFile('passport_photo') && $request->file('passport_photo')->isValid()) {
            $this->deleteOldAttachment($employee, self::ATTACHMENT_TYPES['passport_photo']);

            $photo = $request->file('passport_photo');
            $filename = 'passport_' . time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('attachments/employees/profile_photos', $filename, 'public');
            $employee->update(['profile_picture' => $filename]);

            $attachments[] = [
                'filename' => $filename,
                'path'     => $path,
                'type'     => self::ATTACHMENT_TYPES['passport_photo'],
            ];

            Log::info('Updated passport photo attachment:', $attachments);
        }

        // Handle other single-file document uploads.
        $this->handleDocumentUpload($request, $attachments, 'tin_document', self::ATTACHMENT_TYPES['tin_document'], $employee);
        $this->handleDocumentUpload($request, $attachments, 'national_id_document', self::ATTACHMENT_TYPES['national_id_document'], $employee);
        $this->handleDocumentUpload($request, $attachments, 'cv_document', self::ATTACHMENT_TYPES['cv_document'], $employee);

        // Handle replacement of all certificates.
        if ($request->hasFile('certificates')) {
            // Delete all existing certificates.
            $oldCerts = $employee->attachments()->where('type', self::ATTACHMENT_TYPES['certificate'])->get();
            foreach ($oldCerts as $cert) {
                Log::info("Deleting old certificate: {$cert->path}");
                Storage::disk('public')->delete($cert->path);
                $cert->delete();
            }

            foreach ($request->file('certificates') as $index => $certificate) {
                if ($certificate && $certificate->isValid()) {
                    $filename = 'certificate_' . $index . '_' . time() . '.' . $certificate->getClientOriginalExtension();
                    $path = $certificate->storeAs('attachments/employees', $filename, 'public');

                    $attachments[] = [
                        'filename' => $filename,
                        'path'     => $path,
                        'type'     => self::ATTACHMENT_TYPES['certificate'],
                    ];
                }
            }
        }

        // Save all newly uploaded attachments.
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
                'status'  => 'error',
                'message' => 'User not found for the selected employee'
            ]);
        }

        $employee->user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->back()->with([
            'status'  => 'success',
            'message' => 'Password updated successfully'
        ]);
    }

    public function updatePassportPhoto(Request $request, $id)
    {
        $request->validate([
            'passport_photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
        ]);

        $employee = EmployeeTrait::getEmployeeById($id);

        if (!$employee) {
            return redirect()->back()->with([
                'status'  => 'error',
                'message' => 'Employee not found'
            ]);
        }

        if ($request->hasFile('passport_photo') && $request->file('passport_photo')->isValid()) {
            // Delete the old passport photo if it exists.
            $this->deleteOldAttachment($employee, self::ATTACHMENT_TYPES['passport_photo']);

            // Upload the new passport photo.
            $photo = $request->file('passport_photo');
            $filename = 'passport_' . time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('attachments/employees/profile_photos', $filename, 'public');

            // Update the employee's profile picture.
            $employee->update(['profile_picture' => $filename]);

            // Save the new attachment.
            $employee->attachments()->create([
                'filename' => $filename,
                'path'     => $path,
                'type'     => self::ATTACHMENT_TYPES['passport_photo'],
            ]);

            Log::info('Updated passport photo for employee ID ' . $id, ['filename' => $filename, 'path' => $path]);

            return redirect()->route('admin.employees.show', $employee->id)
                ->with('success', 'Passport photo updated successfully');
        }

        return redirect()->back()->with([
            'status'  => 'error',
            'message' => 'Invalid passport photo upload'
        ]);
    }
}
