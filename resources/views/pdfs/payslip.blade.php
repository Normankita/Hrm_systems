<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->employee->full_name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: #333;
            background-color: #fff;
            padding: 40px;
            margin: 0;
        }

        .payslip {
            max-width: 800px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        h2,
        h4 {
            text-align: center;
            margin: 5px 0;
        }

        .section-title {
            margin-top: 30px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .details-table,
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .details-table td,
        .salary-table th,
        .salary-table td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        .salary-table th {
            background-color: #f5f5f5;
            text-align: left;
        }

        .summary {
            margin-top: 20px;
            font-weight: bold;
            text-align: right;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 30px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="payslip">
        <h2>Company Name</h2>
        <h4>Employee Payslip</h4>
        <p style="text-align:center;"><strong>Period:</strong> {{ $payroll->period }}</p>

        <div class="section-title">Employee Details</div>
        <table class="details-table">
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ $payroll->employee->full_name }}</td>
                <td><strong>Employee ID:</strong></td>
                <td>{{ $payroll->employee->employee_no }}</td>
            </tr>
            <tr>
                <td><strong>Designation:</strong></td>
                <td>{{ $payroll->employee->designation->name ?? '-' }}</td>
                <td><strong>Pay Grade:</strong></td>
                <td>{{ $payroll->payGrade->name ?? '-' }}</td>
            </tr>
        </table>

        <div class="section-title">Salary Breakdown</div>
        <table class="salary-table">
            <thead>
                <tr>
                    <th>Earnings</th>
                    <th>Amount (TZS)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary</td>
                    <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                </tr>
                <tr>
                    <td>Allowances</td>
                    <td>{{ number_format($payroll->allowances, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Gross Salary</strong></td>
                    <td><strong>{{ number_format($payroll->gross_salary, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Deductions</div>
        <table class="salary-table">
            <thead>
                <tr>
                    <th>Deduction Type</th>
                    <th>Amount (TZS)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalCustomDeductions = 0;
                @endphp

                <tr>
                    <td>PAYE</td>
                    <td>{{ number_format($payroll->paye, 2) }}</td>
                </tr>
                <tr>
                    <td>NSSF</td>
                    <td>{{ number_format($payroll->nssf, 2) }}</td>
                </tr>
                <tr>
                    <td>PSSSF</td>
                    <td>{{ number_format($payroll->psssf, 2) }}</td>
                </tr>
                <tr>
                    <td>SDL</td>
                    <td>{{ number_format($payroll->sdl, 2) }}</td>
                </tr>
                <tr>
                    <td>WCF</td>
                    <td>{{ number_format($payroll->wcf, 2) }}</td>
                </tr>

                @if ($payroll->deductions instanceof \Illuminate\Support\Collection)
                    @foreach ($payroll->deductions as $deduction)
                        <tr>
                            <td>{{ $deduction->name }}</td>
                            <td>{{ number_format($deduction->pivot->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                @endif


                @php
                    $totalStatutory = $payroll->paye + $payroll->nssf + $payroll->psssf + $payroll->sdl + $payroll->wcf;
                    $totalDeductions = $totalStatutory + $totalCustomDeductions;
                @endphp

                <tr>
                    <td><strong>Total Deductions</strong></td>
                    <td><strong>{{ number_format($totalDeductions, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="summary">
            <p>Net Salary: <strong>{{ number_format($payroll->net_salary, 2) }} TZS</strong></p>
        </div>

        <div class="footer">
            Generated on {{ now()->format('d M Y H:i') }} | This is a system-generated payslip.
        </div>
    </div>
</body>

</html>
