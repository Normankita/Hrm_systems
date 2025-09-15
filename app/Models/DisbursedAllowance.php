<?php

namespace App\Models;

use App\Enums\AllowanceGroups;
use App\Http\Utils\Traits\onBootTrait;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;

class DisbursedAllowance extends Model
{

    use onBootTrait;
    // create a enum that i can user for type ['individual', 'group', '']

    public function __construct()
    {

    }

    protected $fillable = [
        'type',
        'amount',
        'company_id',
        'employee_id',
        'status',
        'disbursable_id',
        'disbursable_type',
        'allowance_id',
    ];

    public function disbursable()
    {
        return $this->morphTo();
    }

    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public static function getIndividialDisbursements()
    {
        return self::where('type', AllowanceGroups::INDIVIDUAL)->get();
    }

    public static function getGroupDisbursements()
    {
        return self::where('type', AllowanceGroups::GROUP)->get();
    }

    public static function getCategorizedDisbursements()
    {
        return self::where('type', AllowanceGroups::CATEGORY)->get();
    }


    public function getDisbursementDay()
    {
        return $this->create_at;
    }

}
