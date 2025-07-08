<?php

namespace App\Http\Utils\Traits;

use App\Models\DisbursedAllowance;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasAllowanceDisbursements
{
    /**
     * Get all of the model's allowance disbursements.
     */
    public function allowanceDisbursements(): MorphMany
    {
        return $this->morphMany(DisbursedAllowance::class, 'disbursable');
    }
}
