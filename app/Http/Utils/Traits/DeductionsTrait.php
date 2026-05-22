<?php

namespace App\Http\Utils\Traits;

use App\Models\Contribution;
use App\Models\Employee;

trait DeductionsTrait
{

    /**
     * Summary of getAllDeductions
     * This function returns the deductions that are supposed to
     * be taken away from our employee's salary as array of statutory and deductions
     * @param \App\Models\Employee $employee
     * @return array{deductions: \Illuminate\Support\Collection<TKey, mixed>, statutory: \Illuminate\Support\Collection<TKey, mixed>|null}
     */
    public static function getAllDeductions(Employee $employee)
    {
        $activePayGrade = $employee->getActivePayGrade();
        if (!$activePayGrade) {
            return null;
        }
        // Determine base salary
        $basic = $employee->getBaseSalary();

        // getting statutory contributions
        $statutory = self::statutoryContributions($basic);

        $deductionsToAttach = self::getDeductions($employee);
        return [
            'statutory' => collect($statutory),
            'deductions' => collect($deductionsToAttach)
        ];
    }



    /**
     * getDeductions
     * This function returns (ONLY) deductions (Deduction)
     * that are supposed to be taken away from our employee's salary
     * @param \App\Models\Employee $employee
     * @return array
     */
    public static function getDeductions(Employee $employee)
    {
        $deductionsToAttach = [];
        foreach ($employee->deductions as $deduction) {
            $appliedCount = $deduction->payrolls()
                ->wherePivot('deduction_id', $deduction->id)
                ->count();

            if ($appliedCount < $deduction->installments) {
                $deductionsToAttach[] = $deduction;
            }
        }
        return $deductionsToAttach;
    }


    /**
     * This function return number of the completed installments
     * @return int
     */
    private function getCompletedInstallments(Employee $employee): int {
        $deductions = self::getDeductions($employee);
        return count($deductions);
    }


    /**
     * statutoryContributions
     * This function returns (ONLY) statutory contributions
     * that are supposed to be taken away from our employee's salary
     * @param mixed $baseSalary
     * @return array{nssf: float|int, paye: float|int, psssf: float|int, sdl: float|int, wcf: float|int}
     */
    public static function statutoryContributions($baseSalary)
    {
        $contributions = Contribution::pluck('percent', 'name');
        $paye = $baseSalary * ($contributions['PAYE'] ?? 0) / 100;
        $nssf = $baseSalary * ($contributions['NSSF'] ?? 0) / 100;
        $psssf = $baseSalary * ($contributions['PSSSF'] ?? 0) / 100;
        $sdl = $baseSalary * ($contributions['SDL'] ?? 0) / 100;
        $wcf = $baseSalary * ($contributions['WCF'] ?? 0) / 100;
        $statutory = [
            'paye' => $paye,
            'nssf' => $nssf,
            'psssf' => $psssf,
            'sdl' => $sdl,
            'wcf' => $wcf
        ];
        return $statutory;
    }


    /**
     * Summary of getPaidAmount
     * This function returns the total amount paid by the employee
     * from his salary
     * @return float
     */
    private function getPaidAmount(Employee $employee) : float {
        $deductions = self::getDeductions($employee);
        $deductions = collect($deductions);
        $count = $deductions->sum('pivot.total_amount');
        return $count;
    }



    public function getRemainingDeductions(Employee $employee)
    {
        $deductionsToAttach = self::getDeductions($employee);

    }
}
