<?php

namespace App\Services;

use App\Models\Deduction;

class DeductionService
{
    public static function createDeduction(array $data): Deduction
    {
        return Deduction::create($data);
    }

    public static function updateDeduction(Deduction $deduction, array $data): bool
    {
        return $deduction->update($data);
    }

    public static function deleteDeduction(Deduction $deduction): bool
    {
        // Optionally detach from payrolls if needed
        $deduction->payrolls()->detach();
        return $deduction->delete();
    }
}
