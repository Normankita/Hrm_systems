<?php

namespace App\Models;

use App\Http\Utils\Traits\HasDateFilter;
use App\Http\Utils\Traits\HasEvents;
use App\Http\Utils\Traits\LeaveTrait;
use App\Http\Utils\Traits\onBootTrait;
use App\Models\Scopes\AuthUserCompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasDateFilter, HasEvents, onBootTrait;

    protected $fillable = [
        'user_id',
        'company_id',
        'department_id',
        'attendance_session_id',
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
        'userStatus',
        'state',
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
     * Function to return Employees who are currently on a leave
     * @return \Illuminate\Database\Eloquent\Collection<int, Employee>
     */
    public static function getRecentLeaveRequest()
    {
        $today = Carbon::today();
        $latestEmployeeLeaveRequests = self::with('leaves')
            ->whereHas('leaves', function ($query) use ($today) {
                $query->where('start_date', '<=', $today)->where('end_date', '>=', $today);
            })
            ->latest()
            ->take(10)
            ->get();
        return $latestEmployeeLeaveRequests;
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
        $leaves = $this->leaves()
            ->where('status', 'approved')
            ->whereHas('leaveType', function ($query) {
                $query->where('leave_types.deducts_from_annual_leave', true);
            })
            ->with('leaveType')
            ->whereYear('start_date', $thisYear)
            ->get();
        return $leaves;
    }


    public function getLeaveBalance()
    {
        $spentLeaves = $this->getSpentLeaves();
        $leaveDays = session()->get('leave_days', 0);
        $daysCount = LeaveTrait::getStaticLeaveDaysCount($spentLeaves);

        return max(0, $leaveDays - $daysCount);
    }


    /**
     * Summary of getUnCompensatedLeaves
     * @return \Illuminate\Database\Eloquent\Collection<int, Leave>
     */
    public function getUnCompensatedLeaves()
    {
        return Leave::where(
            'employee_id',
            $this->id
        )
            ->whereHas('leaveType', function ($query) {
                $query->where('is_compensated', false)
                    ->where('deducts_from_annual_leave', false);
            })
            ->get();
    }


    /**
     * Summary of getCompensatedLeaves
     * @return \Illuminate\Database\Eloquent\Collection<int, Leave>
     */
    public function getCompensatedLeaves()
    {
        // require compansation
        return Leave::where(
            'employee_id',
            $this->id
        )
            ->whereHas('leaveType', function ($query) {
                $query->where('is_compensated', true)
                    ->where('deducts_from_annual_leave', false);
            })
            ->get();
    }

    public function pay_grades()
    {
        return $this->belongsToMany(PayGrade::class)
            ->withPivot(['id', 'status', 'assigned_by', 'effective_from', 'base_salary_override'])->withTimestamps();
    }


    public function allowances()
    {
        return $this->belongsToMany(Allowance::class, 'employee_allowance')
            ->withPivot(['id', 'amount', 'allowance_frequency_id', 'status', 'effective_date'])
            ->withTimestamps();
    }


    public function employeeAllowances()
    {
        return $this->hasMany(EmployeeAllowance::class);
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


    public static function getActiveEmployees()
    {
        return self::where('state', 'active')
            ->where('userStatus', true)
            ->get()
            ->map(function ($employee) {
                return $employee->isCurrentlyOnLeave() ? null : $employee;
            })
            ->filter()
            ->values();
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
            ->withPivot(['isActive'])
            ->withTimestamps();
    }


    public function disbursedAllowances()
    {
        return $this->hasMany(DisbursedAllowance::class);
    }


    public function allowanceGroupEmployeePivots()
    {
        return $this->hasMany(AllowanceGroupEmployeePivot::class, 'employee_id');
    }


    public function absentAllowance()
    {
        return Allowance::whereNotIn(
            'id',
            $this->allowances()->pluck('allowance_id')
        )
            ->get();
    }



    /**
     * The function returns the employees who attended today
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function whoAttendToday()
    {
        $today = Carbon::now()->format('Y-m-d');
        $todayAttendance = Attendance::with('employee')
            ->whereDate('attendance_date', $today)
            ->whereIn('status', ['present', 'late'])
            ->get();
        $employees = $todayAttendance->pluck('employee');
        return $employees;
    }



    public static function whoCheckoutToday()
    {
        $today = Carbon::now()->format('Y-m-d');
        $todayAttendance = Attendance::with('employee')
            ->whereDate('attendance_date', $today)
            ->whereNotNull('check_out_time')
            ->get();
        $employees = $todayAttendance->pluck('employee');
        return $employees;
    }


    /**
     * The function checks if the employee was on leave on a specific day
     * @param mixed $day
     * @return bool
     */
    public function wasOnLeave($day)
    {
        $day = Carbon::parse($day)->format('Y-m-d');
        return $this->leaves()
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $day)
            ->whereDate('end_date', '>=', $day)
            ->exists();
    }


    /**
     * Checks if the employee is on leave today.
     *
     * @return bool
     */
    public function isOnLeaveToday()
    {
        $today = Carbon::now()->format('Y-m-d');
        return $this->wasOnLeave($today);
    }


    public function attendanceSession()
    {
        return $this->belongsTo(AttendanceSession::class, 'attendance_session_id');
    }


    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }


    /**
     * Summary of getTodayAttendance
     * @return Attendance|object|null
     */
    public function getTodayAttendance()
    {
        $today = Carbon::now()->format('Y-m-d');
        return $this->attendances()
            ->whereDate('attendance_date', $today)
            ->first();
    }

    public function oldEmployeeShifts()
    {
        return $this->hasMany(OldEmployeeShift::class, 'employee_id');
    }

    public function role() 
    {
        $user = $this->user;
        return $user->activeRole();
    }

}
