<?php
namespace App\Http\Services;

use App\Http\Utils\Traits\PdfTrait;
use App\Models\Payroll;

class PayslipPdfService
{
    public function generate(Payroll $payroll): string
    {

        $data = [
            'payroll' => $payroll->load([
                'employee',
                'pay_grade',
                'deductions' => fn($q) => $q->withPivot('total_amount'),
            ])
        ];
        $filename = $payroll->employee->full_name . '_' . $payroll->period;
        // This now returns the storage path directly
        return PdfTrait::generatePdf('pdfs.payslip', $filename, $data, store: true);

    }
}

