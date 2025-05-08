<?php

namespace App\Http\Utils\Traits;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

trait EmployeeTrait
{

    /**
     * Summary of createEmployee
     * This function takes the employee data and creates a new employee
     * @param array $data
     * @return Employee
     */
    public static function createEmployee($data): Employee
    {
        // start by creating a user account first
        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make(strtolower($data['last_name'])),
            'company_id' => $data['company_id'],
        ]);
        $employeeRole = Role::findById($data['role_id']);
        $user->assignRole($employeeRole);
        $data['user_id'] = $user->id;
        $employee = Employee::create($data);
        return $employee;
    }


    public function storeEmployee(Request $request, $attachmentsNamesArray,
         callable $handleDocumentUpload = null ) {
        $attachments = [];

        // Handle passport photo upload using our helper method.
        $this->handlePassportToProfilePhotoUpload($request);

        // Merge additional fields before creating the employee.
        $request->merge([
            'company_id' => Auth::user()->company_id,
            'full_name' => $request->input('first_name') . ' ' . $request->input('last_name'),
        ]);

        $employee = $this->createEmployee($request->all());

        foreach ($attachmentsNamesArray as $key => $value) {
            $file = $request->file($key);
            $handleDocumentUpload ? $handleDocumentUpload($file, $value, $attachments) : fn() => true;

            // Delete the old document of this type if it exists.
            $this->deleteOldAttachment($employee, $value);
        }

        // Save all attachments to the employee.
        foreach ($attachments as $attachment) {
            $employee->attachments()->create($attachment);
        }

        return [
            'employee' => $employee,
        ];

    }


    public static function getEmployeeById($id): Employee
    {
        // Find the employee by ID
        $employee = Employee::where('id', $id)->first();
        return $employee;
    }

    public static function updateEmployee($id, $data)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return null; // or throw exception
        }
        $user = User::find($employee->user_id);

        if ($user) {
            $user->update([
                'name' => $data['full_name'] ?? ($data['first_name'] . ' ' . $data['last_name']),
                'email' => $data['email'],
            ]);
        }
        $employee->update($data);
        return $employee;
    }




    private function getNamesFromFullName($fullName): array
    {
        // Split full name
        $nameParts = explode(' ', $fullName, 3); // Only split into 3 parts: first and last
        $first_name = $nameParts[0];
        $middle_name = $nameParts[1] ?? '';
        $last_name = $nameParts[2] ??'';
        $nameParts = [

            'first_name' => $first_name,
            'middle_name'=> $middle_name,
            'last_name' => $last_name,
        ];
        return $nameParts;
    }



}
