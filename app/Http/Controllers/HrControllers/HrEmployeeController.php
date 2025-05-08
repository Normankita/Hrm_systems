<?php

namespace App\Http\Controllers\HrControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Utils\Traits\EmployeeTrait;
use App\Http\Utils\Traits\UploadFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Log;
use Spatie\Permission\Models\Role;

class HrEmployeeController extends Controller
{
    use EmployeeTrait, UploadFileTrait;

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

        return view('hr.employee.index', compact('employees'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'ADMIN')->get();
        return view('hr.employee.create', compact('roles'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $outcome = $this->storeEmployee($request, self::ATTACHMENT_TYPES,
        fn($file, $value, &$attachments) =>   $this->handleDocumentUpload($file, $value, $attachments));

        return redirect()->route('employees.show', $outcome['employee']->id)
            ->with('success', 'Employee created successfully');
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $attachments = [];

        $request->merge([
            'company_id' => Auth::user()->company_id,
            'full_name'  => $request->input('first_name') . ' ' . $request->input('last_name'),
        ]);

        $employee = EmployeeTrait::getEmployeeById($id);
        $employee->update($request->all());

        if ($request->hasFile('passport_photo')) {
            $this->deleteOldAttachment($employee, self::ATTACHMENT_TYPES['passport_photo']);
            $this->handlePassportPhotoUpload($request, $attachments, $employee);
        }

        $this->handleDocumentUpload($request, $attachments, 'tin_document', self::ATTACHMENT_TYPES['tin_document'], $employee);
        $this->handleDocumentUpload($request, $attachments, 'national_id_document', self::ATTACHMENT_TYPES['national_id_document'], $employee);
        $this->handleDocumentUpload($request, $attachments, 'cv_document', self::ATTACHMENT_TYPES['cv_document'], $employee);

        $this->replaceCertificates($request, $attachments, $employee);

        foreach ($attachments as $attachment) {
            $employee->attachments()->create($attachment);
        }

        return redirect()->route('employees.show', $employee->id)
            ->with('success', 'Employee updated successfully');
    }

    public function show($id)
    {
        $employee = EmployeeTrait::getEmployeeById($id);
        $attachments = $employee->attachments()->get();

        return view('hr.employee.show', compact('employee', 'attachments'));
    }


    private function handlePassportPhotoUpload(Request $request, array &$attachments)
    {
        if ($request->hasFile('passport_photo') && $request->file('passport_photo')->isValid()) {
            $photo = $request->file('passport_photo');
            $filename = 'passport_' . time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('attachments/employees/profile_photos', $filename, 'public');

            $request->merge(['profile_picture' => $filename]);

            $attachments[] = [
                'filename' => $filename,
                'path'     => $path,
                'type'     => self::ATTACHMENT_TYPES['passport_photo'],
            ];
        }
    }


    private function handleCertificatesUpload(Request $request, array &$attachments)
    {
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
    }

    private function replaceCertificates(Request $request, array &$attachments, $employee)
    {
        if ($request->hasFile('certificates')) {
            $oldCerts = $employee->attachments()->where('type', self::ATTACHMENT_TYPES['certificate'])->get();
            foreach ($oldCerts as $cert) {
                Storage::disk('public')->delete($cert->path);
                $cert->delete();
            }

            $this->handleCertificatesUpload($request, $attachments);
        }
    }

    private function deleteOldAttachment($employee, string $type)
    {
        $old = $employee->attachments()
            ->where('attachmentable_type', 'App\Models\Employee')
            ->where('attachmentable_id', $employee->id)
            ->where('type', $type)
            ->first();

        if ($old) {
            Log::info("Deleting old {$type} file: {$old->path}");
            Storage::disk('public')->delete($old->path);
            $old->delete();
        }
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

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
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

            return redirect()->route('hr.employees.show', $employee->id)
                ->with('success', 'Passport photo updated successfully');
        }

        return redirect()->back()->with([
            'status'  => 'error',
            'message' => 'Invalid passport photo upload'
        ]);
    }
}
