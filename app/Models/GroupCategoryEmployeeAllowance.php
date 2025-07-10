<?php

namespace App\Models;

use App\Http\Resources\AllowanceGroupAllowancePivotResource;
use App\Http\Resources\AllowanceGroupEmployeePivotResource;
use App\Http\Utils\Traits\HasAllowanceDisbursements;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GroupCategoryEmployeeAllowance extends Model
{

    use HasAllowanceDisbursements;

    protected $fillable = [
        'allowance_group_employee_pivot_id',
        'allowance_group_allowance_pivot_id',
        'amount',
        'allowance_frequency_id',
        'effective_from',
        'status'
    ];

    public function group_employee_pivot()
    {
        return $this->belongsTo(
            AllowanceGroupEmployeePivot::class,
            'allowance_group_employee_pivot_id',
            'id'
        );
    }

    public function group_allowance_pivot()
    {
        return $this->belongsTo(
            AllowanceGroupAllowancePivot::class,
            'allowance_group_allowance_pivot_id',
            'id'
        );
    }

    public function frequency()
    {
        return $this->belongsTo(AllowanceFrequency::class, 'allowance_frequency_id');
    }


    public static function getAllInIds(array $ids)
    {
        $selfObjectes = GroupCategoryEmployeeAllowance::whereIn(
            'id',
            $ids
        )->get();
        return $selfObjectes->map(function ($item) {
            return self::getRealDetails($item->id);
        });
    }

    public static function getRealDetails(int $id)
    {
        $output = GroupCategoryEmployeeAllowance::with(
            [
                'group_employee_pivot',
                'group_allowance_pivot',
                'frequency'
            ]
        )
            ->find($id);
        if (!$output) {
            return Collection::make([]);
        }
        $output->group_employee_pivot_details = AllowanceGroupEmployeePivot::getRealDetails($output->group_employee_pivot->id);
        $output->group_allowance_pivot_details = AllowanceGroupAllowancePivot::getRealDetails($output->group_allowance_pivot->id);
        return self::realDetailsFormat($output);
    }


    public function getRealDetailsDynamic()
    {
        $output = GroupCategoryEmployeeAllowance::with(
            [
                'group_employee_pivot',
                'group_allowance_pivot',
                'frequency'
            ]
        )
            ->find($this->id);
        if (!$output) {
            return Collection::make([]);
        }
        $output->group_employee_pivot_details = new AllowanceGroupEmployeePivotResource(AllowanceGroupEmployeePivot::getRealDetails($output->group_employee_pivot->id));
        $output->group_allowance_pivot_details = new AllowanceGroupAllowancePivotResource(AllowanceGroupAllowancePivot::getRealDetails($output->group_allowance_pivot->id));
        return $this->realDetailsFormat($output);
    }


    private static function realDetailsFormat($output) {
        return (object) [
            'id' => $output->id,
            'amount' => $output->amount,
            'effective_from' => $output->effective_from,
            'isActive' => $output->isActive,
            'employee' => $output->group_employee_pivot_details->employee,
            'allowance' => $output->group_allowance_pivot_details->allowance,
            'group' => $output->group_employee_pivot_details->group,
            'frequency' => $output->frequency,
            'group_employee_pivot' => $output->group_employee_pivot_details,
            'group_allowance_pivot' => $output->group_allowance_pivot_details,
            'created_at' => $output->created_at,
            'object' => $output
        ];
    }

}
