<?php

namespace App\Models;

use App\Http\Utils\Traits\HasDateFilter;
use App\Models\Scopes\AuthUserCompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasDateFilter;

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

    public static function countEmployeesCurrentlyOnLeave(): int
    {
        return static::whereHas('leaves', function ($query) {
            $today = Carbon::today();
            $query->where('status', 'approved')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today);
        })->count();
    }
    /**
     * Check if the employee is currently on leave
     *
     * @return bool
     */

    public function isCurrentlyOnLeave(): bool
    {
        $today = Carbon::today();

        return $this->leaves()
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

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
            ->with('leaveType', function ($query) {
                $query->where('is_compensated', false);
            })
            ->whereYear('start_date', $thisYear)
            ->get();
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

    public function statusHistories()
    {
        return $this->hasMany(EmployeeStatusHistory::class);
    }

    public function currentStatus()
    {
        return $this->hasOne(EmployeeStatusHistory::class)
            ->where('isActive', true);
    }

    public function getApprovedMonthPayrolls(Carbon $month)
    {
        return $this->payrolls()
            ->where('period', $month->format('Y-m'))
            ->where('status', 'approved')
            ->first()->net_salary ?? 0;
    }

    public function leaveApprovals()
    {
        return $this->hasMany(LeaveApproval::class);
    }

    public function allowance_groups()
    {
        return $this->belongsToMany(
            AllowanceGroup::class,
            'allowance_group_employee'
        )
            ->withPivot(['amount', 'isActive'])
            ->withTimestamps();
    }

}
