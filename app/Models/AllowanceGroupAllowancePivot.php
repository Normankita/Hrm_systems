<?php

namespace App\Models;

use App\Http\Utils\Traits\HasEvents;
use App\Models\AllowanceGroupEmployeePivot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AllowanceGroupAllowancePivot extends Model
{
    use HasEvents;

    protected $table = 'allowance_group_allowance';

    protected $fillable = [
        'allowance_group_id',
        'allowance_id',
        'isActive'
    ];


    /**
     * Gets a group_allowance pivot table's allowance object
     * @return Allowance|\Illuminate\Database\Eloquent\Collection<int, Allowance>|null
     */
    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }

    /**
     * Defines a relationship to the AllowanceGroup model
     * using 'allowance_group_id' as the foreign key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<AllowanceGroup>
     */

    public function group()
    {
        return $this->belongsTo(
            AllowanceGroup::class,
            'allowance_group_id',
            'id'
        );
    }


    /**
     * Gets a group_allowance pivot table's allowance object
     * @return Allowance|\Illuminate\Database\Eloquent\Collection<int, Allowance>|null
     */
    public function getAllowance()
    {
        return Allowance::find($this->allowance_id);
    }

    /**
     * Gets  group_allowance pivot table's allowance object from collection
     * @param \Illuminate\Support\Collection<int, \App\Models\AllowanceGroupAllowancePivot> $gr_allowances
     * @return \Illuminate\Database\Eloquent\Collection<int, Allowance>
     */
    public static function getAllowances(Collection $gr_allowance_pivots)
    {
        $gr_allowanceIds = $gr_allowance_pivots->pluck('allowance_id');
        return Allowance::whereIn('id', $gr_allowanceIds)->get();
    }



    public function getGroup()
    {
        return AllowanceGroup::find($this->allowance_group_id);
    }


    /**
     * Gets  group_allowance pivot table's allowanceGroup object from collection
     * @param \Illuminate\Support\Collection<int, \App\Models\AllowanceGroupAllowancePivot> $gr_allowances
     * @return \Illuminate\Database\Eloquent\Collection<int, AllowanceGroup>
     */
    public function getGroups(Collection $gr_allowance_pivots)
    {
        $gr_allowanceIds = $gr_allowance_pivots->pluck('allowance_group_id');
        return AllowanceGroup::whereIn('id', $gr_allowanceIds)->get();
    }


    /**
     * Gets group_employeePivot objects that are assigned to this group allowance
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<AllowanceGroupEmployeePivot, AllowanceGroupAllowancePivot, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function groupEmployeesPivot()
    {
        return $this->belongsToMany(
            AllowanceGroupEmployeePivot::class,
            'group_category_employee_allowances',
            'allowance_group_allowance_pivot_id',
            'allowance_group_employee_pivot_id'
        )
            ->withPivot([
                'id',
                'allowance_frequency_id',
                'amount',
                'effective_from',
                'isActive'
            ])
            ->withTimestamps();
    }

    /**
     * Gets group_employeePivot objects that are assigned to this group allowance
     * that are active only
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<AllowanceGroupEmployeePivot, AllowanceGroupAllowancePivot, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function activeGroupEmployeesPivot()
    {
        return $this->belongsToMany(
            AllowanceGroupEmployeePivot::class,
            'group_category_employee_allowances',
            'allowance_group_allowance_pivot_id',
            'allowance_group_employee_pivot_id'
        )
            ->withPivot(['id', 'allowance_frequency_id', 'amount', 'effective_from', 'isActive'])
            ->withTimestamps()
            ->wherePivot('isActive', true);
    }


    public static function getRealDetails($id)
    {
        return AllowanceGroupAllowancePivot::select('id', 'allowance_id')
            ->with(
                ['allowance']
            )
            ->find($id);
    }
}
