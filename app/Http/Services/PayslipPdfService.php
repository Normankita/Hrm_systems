<?php
namespace App\Http\Services;

use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PayslipPdfService
{

    public function generate(Payroll $payroll): string
    {
        $data = ['payroll' => $payroll->load('employee', 'pay_grade', 'deductions')];
        dd("I am here");
        $hello = Pdf::loadView('pdfs.payslip', $data);
        dd($hello);
    }


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


        $pdf = Pdf::loadView('pdfs.samplePdf');
        $pdf->output();

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(500, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null,
             10, [0, 0, 0]);

        $filename = 'datedSales_' . time() . '.pdf';
        return $pdf->download($filename);  // return $pdf->download($filename) if you need to downlaod the pdf

    }
}
