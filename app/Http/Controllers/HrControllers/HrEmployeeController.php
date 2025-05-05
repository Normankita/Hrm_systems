<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Utils\Traits\EmployeeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Log;

class HrEmployeeController extends Controller
{
    use EmployeeTrait;

    protected const ATTACHMENT_TYPES = [
        'passport_photo'      => 'passport_photo',
        'tin_document'        => 'tin',
        'national_id_document'=> 'national_id',
        'cv_document'         => 'cv',
        'certificate'         => 'certificate',
    ];

    public function store(StoreEmployeeRequest $request)
    {
        $attachments = [];

        $this->handlePassportPhotoUpload($request, $attachments);

        $request->merge([
            'company_id' => Auth::user()->company_id,
            'full_name'  => $request->input('first_name') . ' ' . $request->input('last_name'),
        ]);

        $employee = $this->createEmployee($request->all());

        $this->handleDocumentUpload($request, $attachments, 'tin_document', self::ATTACHMENT_TYPES['tin_document'], $employee);
        $this->handleDocumentUpload($request, $attachments, 'national_id_document', self::ATTACHMENT_TYPES['national_id_document'], $employee);
        $this->handleDocumentUpload($request, $attachments, 'cv_document', self::ATTACHMENT_TYPES['cv_document'], $employee);

        $this->handleCertificatesUpload($request, $attachments);

        foreach ($attachments as $attachment) {
            $employee->attachments()->create($attachment);
        }

        return redirect()->route('employees.show', $employee->id)
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

            Log::info('Passport photo uploaded', $attachments);
        }
    }

    private function handleDocumentUpload(Request $request, array &$attachments, string $fieldName, string $type, $employee)
    {
        if ($request->hasFile($fieldName) && $request->file($fieldName)->isValid()) {
            $this->deleteOldAttachment($employee, $type);

            $file = $request->file($fieldName);
            $filename = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('attachments/employees', $filename, 'public');

            $attachments[] = [
                'filename' => $filename,
                'path'     => $path,
                'type'     => $type,
            ];

            Log::info("Uploaded {$type}", ['filename' => $filename, 'path' => $path]);
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
}
