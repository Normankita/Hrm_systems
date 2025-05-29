<?php

namespace App\Http\Services;

use App\Http\Utils\Traits\EmployeeTrait;
use App\Http\Utils\Traits\UploadFileTrait;
use App\Models\Department;
use App\Models\Employee;
use App\Models\PayGrade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

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

        try {
            $employee = $this->createEmployee($request->all());
        } catch (Throwable $throwable) {
            return [
                'status' => 'fail',
                'message' => $throwable->getMessage()
            ];
        }

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
            $this->handlePassportToProfilePhotoUpload($request);
            return [
                'status' => 'success',
                'message' => 'Profile photo updated Successfully',
                'employee' => $employee
            ];
        }
        return null;
    }


    public function importEmployees(Request $request)
    {

        $path = $request->file('file')
            ->store('itemExcel', 'local');

        if (!(Storage::disk('local')->exists($path))) {
            return [
                'status' => 'error',
                'message' => 'File not found'
            ];
        }
        $file = Storage::path($path);

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Assuming first row is the header
        unset($rows[0]);

        DB::beginTransaction();
        $department = Department::first();

        if (!$department) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Department not found'
            ];
        }

        try {
            foreach ($rows as $row) {
                // start creating user first
                $gender = $row[1] == 0 ? 'female' : 'male';
                $marital_status = $row[6] == 1 ? 'married' : 'single';
                // getting employee type
                switch ($row[9]) {
                    case 0:
                        $employee_type = 'permanent';
                        break;
                    case 1:
                        $employee_type = 'contract';
                        break;
                    case 2:
                        $employee_type = 'probation';
                        break;
                    default:
                        $employee_type = 'permanent';
                        break;
                }

                $company = session('company');

                $salary = $row[11];
                // assign paygrade
                // select paygrade whose salary is between base_salary and max_salary
                $paygrade = PayGrade::where('base_salary', '<=', $salary)
                    ->where('max_salary', '>=', $salary)
                    ->first();
                if (!$paygrade) {
                    $paygrade = PayGrade::first();
                }

                $names = $this->getNamesFromFullName($row[0]);
                $first_name = $names['first_name'];
                $middle_name = $names['middle_name'];
                $last_name = $names['last_name'];

                EmployeeTrait::createEmployee([
                    'full_name' => $row[0],
                    'pay_grade_id' => $paygrade->id,
                    'base_salary_override' => $salary,
                    'company_id' => $company->id,
                    'department_id' => $department->id,
                    'first_name' => $first_name,
                    'middle_name' => $middle_name,
                    'last_name' => $last_name,
                    'gender' => $gender,
                    'date_of_birth' => Carbon::parse($row[2])->format('Y-m-d') ?? '',
                    'phone_number' => $row[3] ?? '',
                    'email' => $row[4] ?? '',
                    'national_id' => $row[5] ?? '',
                    'marital_status' => ucfirst($marital_status),
                    'residential_address' => $row[7] ?? '',
                    'tin_number' => $row[8] ?? '',
                    'employee_type' => $employee_type,
                    'date_of_hire' => Carbon::parse($row[10])->format('Y-m-d') ?? '',
                ]);

                DB::commit();
            }
        } catch (Throwable $throwable) {
            DB::rollBack();
            return ([
                'status' => 'error',
                'message' => $throwable->getMessage()
            ]);
        }

        Storage::disk('local')->delete($file);

        return [
            'status' => 'success',
            'message' => 'Employee imported successfully'
        ];

    }


}
