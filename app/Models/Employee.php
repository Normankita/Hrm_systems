<?php

namespace App\Models;

use App\Models\Scopes\AuthUserCompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Employee extends Model
{

    protected static function booted()
    {
        // Automatically apply a global scope to all queries
        static::addGlobalScope(new AuthUserCompanyScope);

        // Automatically assign the tenant_id when creating a new record
        static::creating(function ($item) {
            if (auth()->check()) {
                if (auth()->user()->hasRole('OWNER')) {

                } else {
                    $company = Company::find(auth()->user()->company_id);
                    if ($company) {
                        $item->company_id = auth()->user()->company_id;
                    }
                }
            }
        });
    }

    protected $fillable = [
        'user_id',
        'company_id',
        'department_id',
        'full_name',
        'gender',
        'date_of_birth',
        'phone_number',
        'email',
        'national_id',
        'marital_status',
        'residential_address',
        'tin_number',
        'employee_type',
        'date_of_hire',
        'date_of_termination',
        'profile_picture',
        'salary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(DesignationRoleMapping::class);
    }

    public function contract()
    {
        return $this->hasOne(EmployeeContract::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }


    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }


    public function attachments()
    {
        return $this->morphMany(
            Attachment::class,
            'attachmentable'
        );
    }


    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }


    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }



    public function getActivePayGrade()
    {
        $payGrades = $this->pay_grades()->latest()->get();
        $activeGrade = $this->pay_grades()->where(
            'status',
            operator: true
        )->first();
        foreach ($payGrades as $payGrade) {
            if ($payGrade->pivot->effective_from <= Carbon::now()) {
                // Set the previous paygrade as inactive
                $activeGrade->pivot->status = false;
                $activeGrade->save();
                // Set the current paygrade as active
                $payGrade->pivot->status = true;
                $payGrade->save();
                return $payGrade;
            }
        }
        return $activeGrade;
    }


    /**
     * Summary of getBaseSalary
     */
    public function getBaseSalary()
    {
        $activePayGrade = $this->getActivePayGrade();
        return $activePayGrade->pivot->base_salary_override > 0
            ? $activePayGrade->pivot->base_salary_override
            : $activePayGrade->base_salary;
    }

    /**
     * Summary of getsSpentLeaves
     * getting the leaves that the employee has taken
     * @param mixed $employee
     */
    public function getSpentLeaves()
    {
        $thisYear = Carbon::now()->year;
        // select all leaves where created at this year
        return $this->leaves()
            ->where('status', 'approved')
            ->whereYear('start_date', $thisYear)
            ->get();
    }


       public static function generatePayrollForSelectedExpected(bool $force = false, $employeeIds = []): array
    {
        $employees = Employee::with([
            'pay_grades' => fn($q) => $q->wherePivot('status', true),
            'deductions',
            'allowances'
        ])->whereIn('id', $employeeIds)->get();

        return self::processPayroll($force, $employees);
    }



    public function pay_grades()
    {
        return $this->belongsToMany(PayGrade::class)
            ->withPivot(['status', 'assigned_by', 'effective_from', 'base_salary_override'])->withTimestamps();
    }

    public function allowances()
    {
        return $this->belongsToMany(Allowance::class, 'employee_allowance')
            ->withPivot(['amount', 'effective_from', 'effective_to', 'frequency', 'status'])
            ->withTimestamps();
    }


    // create a function to generate a year deductions
    public function yearDeductions(Employee $employee, $data, $path) {
        $total = $employee->getAll();
        foreach ($data as $value) {

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
        }

    }

    public static function processPayrollAllowed(bool $force = false, $employees = []): array
    {
        $today = Carbon::today();
        $period = $today->format('Y-m');
        $todayDate = $today->format('Y-m-d');

        $contributions = Contribution::pluck('percent', 'name');
        $generated = [];

        foreach ($employees as $employee) {
            $activePayGrade = $employee->pay_grades->first();

            if (!$activePayGrade)
                continue;

            $basic = $employee->getBaseSalary();

            // Fetch valid employee allowances
            $employeeAllowances = $employee->allowances()
                ->wherePivot('status', true)
                ->wherePivot('effective_from', '<=', $todayDate)
                ->wherePivot('effective_to', '>=', $todayDate)
                ->get();
            $employeeAllowancesTotal = 0;
            $allowancesToAttach = [];

            foreach ($employeeAllowances as $allowance) {
                $employeeAllowancesTotal += $allowance->pivot->amount;

                // Fetch the actual pivot row ID (EmployeeAllowance)
                $employeeAllowance = EmployeeAllowance::where('employee_id', $employee->id)
                    ->where('allowance_id', $allowance->id)
                    ->where('status', true)
                    ->where('effective_from', '<=', $todayDate)
                    ->where('effective_to', '>=', $todayDate)
                    ->first();

                if ($employeeAllowance) {
                    $allowancesToAttach[] = [
                        'employee_allowance_id' => $employeeAllowance->id,
                        'amount' => $allowance->pivot->amount,
                    ];
                }
            }

            // Handle regeneration
            if ($force) {
                Payroll::where('employee_id', $employee->id)->where('period', $period)->delete();
            } else {
                if (Payroll::where('employee_id', $employee->id)->where('period', $period)->exists()) {
                    continue;
                }
            }

            // Statutory deductions
            $paye = $basic * ($contributions['PAYE'] ?? 0) / 100;
            $nssf = $basic * ($contributions['NSSF'] ?? 0) / 100;
            $psssf = $basic * ($contributions['PSSSF'] ?? 0) / 100;
            $sdl = $basic * ($contributions['SDL'] ?? 0) / 100;
            $wcf = $basic * ($contributions['WCF'] ?? 0) / 100;

            $statutory = $paye + $nssf + $psssf + $sdl + $wcf;

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

            $totalDeductions = $statutory + $customDeductions;
            $gross = $basic + $employeeAllowancesTotal;
            $net = $gross - $totalDeductions;

            DB::beginTransaction();
            try {
                $payroll = Payroll::create([
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
                ]);
                foreach ($deductionsToAttach as $item) {
                    $payroll->deductions()->attach($item['id'], [
                        'total_amount' => $item['total_amount']
                    ]);
                }

                // bad zone where allowances in payroll are set to zero

                foreach ($allowancesToAttach as $item) {
                    $payroll->employeeAllowances()->attach($item['employee_allowance_id'], [
                        'amount' => $item['amount']
                    ]);
                }

                // ends here

                $pdfService = new PayslipPdfService();

                $path = $pdfService->generate($payroll);
                $payroll->update(['payslip_path' => $path]);

                DB::commit();
                $generated[] = $payroll;
            } catch (\Throwable $e) {

                dd($e);
                DB::rollBack();
                // optionally log the error: \Log::error($e);
                continue;
            }
        }









}
