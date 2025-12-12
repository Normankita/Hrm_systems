<?php

namespace App\Http\Services;

use App\Http\Utils\Traits\PdfTrait;
use App\Models\Contribution;
use App\Models\ContributionsDivision;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    use PdfTrait;

    public static function generatePayrollForAllEmployees(bool $force = false): array
    {
        $employees = Employee::with([
            'pay_grades' => fn($q) => $q->wherePivot('status', true),
            'deductions',
            'allowances' // eager load with pivot
        ])->get();

        return self::processPayroll($force, $employees);
    }

    public static function generatePayrollForSelectedEmployees(bool $force = false, $employeeIds = []): array
    {
        $employees = Employee::with([
            'pay_grades' => fn($q) => $q->wherePivot('status', true),
            'deductions',
            'allowances'
        ])->whereIn('id', $employeeIds)->get();

        return self::processPayroll($force, $employees);
    }

    public static function processPayroll(
        bool $force = false,
        $employees = []
    ): array {
        // generate a unique reference number for this payroll batch
        $ref = uniqid('', true);
        $today = Carbon::today();
        $period = $today->format('Y-m');

        $contributions = Contribution::select(
            DB::raw("name as name"),
            DB::raw("percent as percent"),
            DB::raw("employee_percent as employee_percent"),
            DB::raw("company_percent as company_percent")
        )
            ->get()
            ->keyBy('name')
            ->toArray();
        $generated = [];

        foreach ($employees as $employee) {
            $activePayGrade = $employee->pay_grades->first();

            if (!$activePayGrade)
                continue;

            $basic = $employee->getBaseSalary();

            // Handle regeneration
            if ($force) {
                Payroll::where('employee_id', $employee->id)->where('period', $period)->delete();
            } else {
                if (Payroll::where('employee_id', $employee->id)->where('period', $period)->exists()) {
                    continue;
                }
            }

            $nssf = $basic * ($contributions['NSSF']['percent'] ?? 0) / 100;
            $psssf = $basic * ($contributions['PSSSF']['percent'] ?? 0) / 100;
            $employee_nssf = $nssf * ($contributions['NSSF']['employee_percent'] ?? 0) / 100;
            $employee_psssf = $psssf * ($contributions['PSSSF']['employee_percent'] ?? 0) / 100;
            $paye = ($basic - $employee_nssf - $employee_psssf) *
                ($contributions['PAYE']['percent'] ?? 0) / 100;

            $sdl = $basic * ($contributions['SDL']['percent'] ?? 0) / 100;
            $wcf = $basic * ($contributions['WCF']['percent'] ?? 0) / 100;

            // Statutory deductions for company side
            $company_nssf = $nssf * ($contributions['NSSF']['company_percent'] ?? 0) / 100;
            $company_psssf = $psssf * ($contributions['PSSSF']['company_percent'] ?? 0) / 100;
            $company_paye = $paye * ($contributions['PAYE']['company_percent'] ?? 0) / 100;
            $company_sdl = $sdl * ($contributions['SDL']['company_percent'] ?? 0) / 100;
            $company_wcf = $wcf * ($contributions['WCF']['company_percent'] ?? 0) / 100;

            // Statutory deductions for employee side
            $employee_paye = $paye * ($contributions['PAYE']['employee_percent'] ?? 0) / 100;
            $employee_sdl = $sdl * ($contributions['SDL']['employee_percent'] ?? 0) / 100;
            $employee_wcf = $wcf * ($contributions['WCF']['employee_percent'] ?? 0) / 100;

            $statutory = $employee_paye + $employee_nssf + $employee_psssf + 
                $employee_sdl + $employee_wcf;

            // Custom deductions (e.g., loans)
            $customDeductions = 0;
            $deductionsToAttach = [];

            foreach ($employee->deductions as $deduction) {
                $appliedCount = $deduction->payrolls()
                    ->wherePivot('deduction_id', $deduction->id)
                    ->count();

                if ($appliedCount < $deduction->installments) {
                    $customDeductions += $deduction->installment_amount;
                    $deductionsToAttach[] = [
                        'id' => $deduction->id,
                        'total_amount' => $deduction->installment_amount,
                    ];
                }
            }

            $employeeAllowancesTotal = 0;
            $totalDeductions = $statutory + $customDeductions;
            $gross = $basic + $employeeAllowancesTotal;
            $net = $gross - $totalDeductions;

            DB::beginTransaction();
            try {
                $payrolldata = [
                    'employee_id' => $employee->id,
                    'pay_grade_id' => $activePayGrade->id,
                    'payroll_date' => $today,
                    'period' => $period,
                    'basic_salary' => $basic,
                    'allowances' => $employeeAllowancesTotal,
                    'deductions' => $customDeductions,
                    'gross_salary' => $gross,
                    'net_salary' => $net,
                    'paye' => $paye,
                    'nssf' => $nssf,
                    'psssf' => $psssf,
                    'sdl' => $sdl,
                    'wcf' => $wcf,
                    'entrence_reference' => $ref,
                ];
                $contributionDivisionData = [
                    'company_nssf' => $company_nssf,
                    'company_psssf' => $company_psssf,
                    'company_paye' => $company_paye,
                    'company_sdl' => $company_sdl,
                    'company_wcf' => $company_wcf,
                    'employee_nssf' => $employee_nssf,
                    'employee_psssf' => $employee_psssf,
                    'employee_paye' => $employee_paye,
                    'employee_sdl' => $employee_sdl,
                    'employee_wcf' => $employee_wcf,
                    'company_id' => $employee->company_id,
                ];
                $payroll = Payroll::create($payrolldata);
                $contributionDivisionData['payroll_id'] = $payroll->id;
                ContributionsDivision::create($contributionDivisionData);
                $payroll->recordEvent('add', $payrolldata);
                foreach ($deductionsToAttach as $item) {
                    $payroll->deductions()->attach($item['id'], [
                        'total_amount' => $item['total_amount']
                    ]);
                }

                $pdfService = new PayslipPdfService();

                $path = $pdfService->generate($payroll);
                $payroll->update(['payslip_path' => $path]);
                DB::commit();
                $generated[] = $payroll;
            } catch (\Throwable $e) {
                DB::rollBack();
                return [
                    'status' => 'error',
                    'error' => $e->getMessage(),
                    'message' => 'Failed to generate payroll for employee : ' . $employee->full_name
                ];
            }
        }
        return [
            'status' => 'success',
            'message' => 'Payrolls generated successfully.',
            'data' => $generated
        ];
    }
}
