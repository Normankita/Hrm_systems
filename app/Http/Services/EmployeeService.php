<?php

namespace App\Http\Services;

use App\Http\Utils\Traits\EmployeeTrait;
use App\Http\Utils\Traits\UploadFileTrait;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeService
{
    use UploadFileTrait, EmployeeTrait;
    public static function getEmployeeById($id): Employee
    {
        // Find the employee by ID
        $employee = Employee::where('id', $id)->first();
        return $employee;
    }

    /**
     * Store a new employee along with attachments.
     *
     * @param Request $request
     * @param array $attachmentsNamesArray
     * @param callable|null $handleDocumentUpload
     * @return array
     */
    public function storeEmployee(
        Request $request,
        $attachmentsNamesArray,
    ) {
        $attachments = [];

        // Handle passport photo upload using our helper method.
        $this->handlePassportToProfilePhotoUpload($request);

        // Merge additional fields before creating the employee.
        $request->merge([
            'company_id' => Auth::user()->company_id,
            'full_name' => $request->input('first_name') . ' ' . $request->input('middle_name') . ' ' . $request->input('last_name'),
        ]);

        $employee = $this->createEmployee($request->all());

        // Employee Attachment creation
        $isCertificatesUploaded = false;

        foreach ($attachmentsNamesArray as $key => $value) {
            $formCertificates = $request->certificates;
            if (!$isCertificatesUploaded && $formCertificates) {
                $isCertificatesUploaded = true;
                foreach ($formCertificates as $index => $certificate) {
                    $this->handleDocumentUpload(
                        $certificate,
                        'certificate',
                        $attachments,
                        ++$index
                    );
                    // Delete the old document of this type if it exists.
                    $this->deleteOldAttachment($employee, 'certificate');
                }
            } else {
                if (
                    $request->hasFile($key)
                ) {
                    $this->handleDocumentUpload(
                        $request->file($key),
                        $value,
                        $attachments,
                    );
                    // Delete the old document of this type if it exists.
                    $this->deleteOldAttachment($employee, $value);
                }
            }
        }

        // Save all attachments to the employee.
        foreach ($attachments as $attachment) {
            $employee->attachments()->create($attachment);
        }

        return [
            'status' => 'success',
            'employee' => $employee,
        ];

    }


    public function updateEmployee(Request $request, $id)
    {
        $request->merge([
            'company_id' => Auth::user()->company_id,
            'full_name' => $request->input('first_name') . ' ' . $request->input('middle_name') . ' ' . $request->input('last_name'),
        ]);

        $employee = EmployeeTrait::updateEmployee($id, $request->all());
        $attachments = [];

        $isCertificatesUploaded = false;
        foreach (self::ATTACHMENT_TYPES as $key => $value) {
            $formCertificates = $request->certificates;
            if (!$isCertificatesUploaded && $formCertificates) {
                $isCertificatesUploaded = true;
                foreach ($formCertificates as $index => $certificate) {
                    $this->handleDocumentUpload(
                        $certificate,
                        'certificate',
                        $attachments,
                        ++$index
                    );
                    // Delete the old document of this type if it exists.
                    $this->deleteOldAttachment($employee, 'certificate');
                }
            } else {
                if (
                    $request->hasFile($key)
                ) {
                    $this->handleDocumentUpload(
                        $request->file($key),
                        $value,
                        $attachments,
                    );
                    // Delete the old document of this type if it exists.
                    $this->deleteOldAttachment($employee, $value);
                }
            }
        }
        // Save all newly uploaded attachments.
        foreach ($attachments as $attachment) {
            $employee->attachments()->create($attachment);
        }
        return [
            'status' => 'success',
            'employee' => $employee,
        ];
    }

    public function UpdateProfilePhoto(Request $request, $id)
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
            return [
                'status' => 'success',
                'message' => 'Profile photo updated Successfully',
                'employee' => $employee
            ];
        }
        return null;
    }

}
