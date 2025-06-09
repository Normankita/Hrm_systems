<?php
namespace App\Http\Services;

use App\Http\Utils\Traits\PdfTrait;
use App\Models\Payroll;

class PayslipPdfService
{

       public static function createReport($request)
    {
        // working with dates if provided
        // $upTo = Carbon::parse($request->upTo)
        //     ->format('Y-m-d');
        // $from = Carbon::parse($request->from)
        //     ->format('Y-m-d');
        // $selected_modes = $request->selected_modes;

        // if($upTo < $from ) {
        //     return redirect()->back()
        //     ->with(['status' => 'error', 'message' => 'upTo date must be large than from date']);
        // }

        // $outStocks = Payroll::where('created_at', '>=', $from)
        //     ->where('created_at', '<=', $upTo)
        //     ->get();

        // return PdfTrait::printPdf(
        //     'pdfs.samplePdf'
        // );

    }
    
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

