<?php

namespace App\Models;

use App\Http\Utils\Traits\onBootTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContractFile;

class EmployeeContract extends Model
{

    use onBootTrait;

    // specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'employee_contracts';

   // Model
    protected $fillable = [
        'employee_id',
        'company_id',
        'contract_number',
        'contract_type_id',
        'department_id',
        'designation_id',
        'start_date',
        'end_date',
        'probation_end_date',
        'basic_salary',
        'currency',
        'payment_frequency',
        'contract_status',
        'termination_reason',
        'signed_date',
        'signed_document',
        'created_by',
        // Additional fields
        'contract_type',    
        'work_location',
        'contract_file_id',
    ];

    // define relation that exists in this model
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function contractType()
    {
        return $this->belongsTo(ContractType::class);
    }

    public function supervisor() {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public static function next_contract_number() {
        $latestContract = self::latest('id')->first();
        return $latestContract ? 'CTR-' . str_pad($latestContract->id + 1, 6, '0', STR_PAD_LEFT) : 'CTR-000001';
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contractFiles()
    {
        return $this->hasMany(ContractFile::class, 'employee_contract_id');
    }

}
