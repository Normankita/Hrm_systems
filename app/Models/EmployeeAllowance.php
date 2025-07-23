<?php

namespace App\Models;

use App\Http\Utils\Traits\HasAllowanceDisbursements;
use App\Http\Utils\Traits\HasEvents;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class EmployeeAllowance extends Model
{
    use HasAllowanceDisbursements, HasEvents;

    protected $table = 'employee_allowance';

    protected $fillable = [
        'employee_id',
        'allowance_frequency_id',
        'allowance_id',
        'amount',
        'status',
        'effective_from',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }

    public function frequency()
    {
        return $this->belongsTo(AllowanceFrequency::class, 'allowance_frequency_id');
    }

    public function payrolls()
    {
        return $this->belongsToMany(
            Payroll::class,
            'employee_allowance_payroll'
        );
    }

    public static function getRealDetails($ids)
    {
        $employeeAllowance = EmployeeAllowance::with(
            [
                'allowance',
                'frequency',
            ]
        )
        ->whereIn('id', $ids)
        ->get();
        return $employeeAllowance->map(function ($item) {
            return (object) [
                'id' => $item->id,
                'amount' => $item->amount,
                'effective_from' => $item->effective_from,
                'isActive' => $item->status,
                'employee' => $item->employee,
                'allowance' => $item->allowance,
                'frequency' => $item->frequency,
                'object' => $item
            ];
        });
    }

}
