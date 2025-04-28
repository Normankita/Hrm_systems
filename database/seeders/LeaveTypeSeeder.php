<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Assuming the seeder is run by an authenticated user with a company
        $companyId = 1;

        

        $leaveTypes = [
            [
                'name' => 'Sick Leave',
                'code' => 'SL',
                'description' => 'Leave for illness or medical reasons',
                'deducts_from_annual_leave' => false,
                'required_approval' => true,
                'eligibility_criteria' => 'Available to all employees',
                'is_compensated' => true,
                'company_id' => $companyId,
            ],
            [
                'name' => 'Casual Leave',
                'code' => 'CL',
                'description' => 'Leave for personal reasons or emergencies',
                'deducts_from_annual_leave' => true,
                'required_approval' => true,
                'eligibility_criteria' => 'Available to all employees',
                'is_compensated' => true,
                'company_id' => $companyId,
            ],
            [
                'name' => 'Annual Leave',
                'code' => 'AL',
                'description' => 'Leave for vacation or personal time off',
                'deducts_from_annual_leave' => true,
                'required_approval' => true,
                'eligibility_criteria' => 'Available to employees with at least 1 year of service',
                'is_compensated' => true,
                'company_id' => $companyId,
            ],
            [
                'name' => 'Maternity Leave',
                'code' => 'ML',
                'description' => 'Leave for maternity purposes',
                'deducts_from_annual_leave' => false,
                'required_approval' => true,
                'eligibility_criteria' => 'Available to female employees',
                'is_compensated' => true,
                'company_id' => $companyId,
            ],
            [
                'name' => 'Paternity Leave',
                'code' => 'PL',
                'description' => 'Leave for paternity purposes',
                'deducts_from_annual_leave' => false,
                'required_approval' => true,
                'eligibility_criteria' => 'Available to male employees',
                'is_compensated' => true,
                'company_id' => $companyId,
            ],
        ];

        DB::table('leave_types')->insert($leaveTypes);
    }
}