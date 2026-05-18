<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;

class ContractFile extends Model
{
    use onBootTrait;
    
    protected $fillable = [
        'company_id',
        'file_path',
        'original_name',
        'employee_contract_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employeeContract()
    {
        return $this->belongsTo(EmployeeContract::class, 'employee_contract_id');
    }
}
