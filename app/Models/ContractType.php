<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;

class ContractType extends Model
{
    use onBootTrait;
    protected $fillable = [
        'company_id',
        'employee_contract_id',
        'name',
        'description',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public static function getContractByName(String $name)
    {
        return self::where('name', $name)->first();
    }

    public static function getOrCreateContractType(String $name)
    {
        $contractType = self::where('name', $name)->first();
        if (!$contractType) {
            $contractType = self::create([
                'name' => $name,
                'company_id' => auth()->user()->company_id,
            ]);
        }
        return $contractType;
    }
}

