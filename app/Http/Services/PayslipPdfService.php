<?php
namespace App\Http\Services;

use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipPdfService
{

     public function generate(Payroll $payroll): string
    {
        $data = ['payroll' => $payroll->load('employee', 'pay_grade', 'deductions')];
        dd("I am here");
        $hello= Pdf::loadView('pdfs.payslip', $data);
        dd($hello);
    }
}
