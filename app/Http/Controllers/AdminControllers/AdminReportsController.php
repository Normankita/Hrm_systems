<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\PdfTrait;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class AdminReportsController extends Controller
{
    use PdfTrait;

    public function index()
    {
        return view('admin.reports.index');
    }

    public function employees()
    {
        return view('admin.reports.employees');
    }

    public function employeesData(Request $request)
    {
        $companyId = $request->user()->company_id;

        $query = Employee::query()
            ->with(['department'])
            ->where('company_id', $companyId);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }

        if ($request->filled('state')) {
            $query->where('state', $request->string('state'));
        }

        if ($request->filled('employee_type')) {
            $query->where('employee_type', $request->string('employee_type'));
        }

        return DataTables::eloquent($query)
            ->addColumn('department', fn (Employee $e) => optional($e->department)->name)
            ->toJson();
    }

    public function employeesExport(Request $request)
    {
        $format = $request->string('format', 'csv')->lower()->value();
        $companyId = $request->user()->company_id;

        $query = Employee::query()
            ->select(['id', 'full_name', 'email', 'phone_number', 'employee_type', 'state', 'department_id', 'date_of_hire'])
            ->with(['department:id,name'])
            ->where('company_id', $companyId);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }

        if ($request->filled('state')) {
            $query->where('state', $request->string('state'));
        }

        if ($request->filled('employee_type')) {
            $query->where('employee_type', $request->string('employee_type'));
        }

        $filenameBase = 'employees_report_' . now()->format('Ymd_His');

        if ($format === 'pdf') {
            $rows = $query->limit(5000)->get();
            return PdfTrait::generatePdf(
                'admin.reports.pdf.employees',
                $filenameBase,
                [
                    'rows' => $rows,
                    'generatedAt' => now(),
                    'note' => $rows->count() >= 5000 ? 'PDF export is limited to the first 5000 rows. Please use CSV for large exports.' : null,
                ],
                download: true
            );
        }

        // For 1m+ rows, CSV streaming is the safest default.
        return $this->streamCsv(
            $query,
            $filenameBase . '.csv',
            [
                'ID',
                'Full Name',
                'Email',
                'Phone',
                'Type',
                'State',
                'Department',
                'Date Of Hire',
            ],
            function (Employee $e) {
                return [
                    $e->id,
                    $e->full_name,
                    $e->email,
                    $e->phone_number,
                    $e->employee_type,
                    $e->state,
                    optional($e->department)->name,
                    optional($e->date_of_hire)?->format('Y-m-d'),
                ];
            }
        );
    }

    public function attendance()
    {
        return view('admin.reports.attendance');
    }

    public function attendanceData(Request $request)
    {
        $companyId = $request->user()->company_id;

        $query = Attendance::query()
            ->with(['employee:id,full_name,department_id', 'employee.department:id,name'])
            ->where('company_id', $companyId);

        if ($request->filled('from')) {
            $query->whereDate('attendance_date', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('attendance_date', '<=', $request->date('to'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->integer('employee_id'));
        }

        return DataTables::eloquent($query)
            ->addColumn('employee', fn ($a) => optional($a->employee)->full_name)
            ->addColumn('department', fn ($a) => optional(optional($a->employee)->department)->name)
            ->toJson();
    }

    public function attendanceExport(Request $request)
    {
        $format = $request->string('format', 'csv')->lower()->value();
        $companyId = $request->user()->company_id;

        $query = Attendance::query()
            ->select(['id', 'employee_id', 'attendance_date', 'check_in_time', 'check_out_time', 'status', 'remarks'])
            ->with(['employee:id,full_name,department_id', 'employee.department:id,name'])
            ->where('company_id', $companyId);

        if ($request->filled('from')) {
            $query->whereDate('attendance_date', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('attendance_date', '<=', $request->date('to'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->integer('employee_id'));
        }

        $filenameBase = 'attendance_report_' . now()->format('Ymd_His');

        if ($format === 'pdf') {
            $rows = $query->orderByDesc('attendance_date')->limit(5000)->get();
            return PdfTrait::generatePdf(
                'admin.reports.pdf.attendance',
                $filenameBase,
                [
                    'rows' => $rows,
                    'generatedAt' => now(),
                    'note' => $rows->count() >= 5000 ? 'PDF export is limited to the first 5000 rows. Please use CSV for large exports.' : null,
                ],
                download: true
            );
        }

        $query->orderByDesc('attendance_date');
        return $this->streamCsv(
            $query,
            $filenameBase . '.csv',
            ['ID', 'Employee', 'Department', 'Date', 'Check In', 'Check Out', 'Status', 'Remarks'],
            function ($a) {
                return [
                    $a->id,
                    optional($a->employee)->full_name,
                    optional(optional($a->employee)->department)->name,
                    optional($a->attendance_date)?->format('Y-m-d'),
                    $a->check_in_time,
                    $a->check_out_time,
                    $a->status,
                    $a->remarks,
                ];
            }
        );
    }

    public function payroll()
    {
        return view('admin.reports.payroll');
    }

    public function payrollData(Request $request)
    {
        $companyId = $request->user()->company_id;

        $query = Payroll::query()
            ->with(['employee:id,full_name'])
            ->where('company_id', $companyId);

        if ($request->filled('period')) {
            $query->where('period', $request->string('period'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->integer('employee_id'));
        }

        return DataTables::eloquent($query)
            ->addColumn('employee', fn (Payroll $p) => optional($p->employee)->full_name)
            ->toJson();
    }

    public function payrollExport(Request $request)
    {
        $format = $request->string('format', 'csv')->lower()->value();
        $companyId = $request->user()->company_id;

        $query = Payroll::query()
            ->select([
                'id',
                'employee_id',
                'period',
                'payroll_date',
                'basic_salary',
                'gross_salary',
                'net_salary',
                'status',
                'approved_at',
            ])
            ->with(['employee:id,full_name'])
            ->where('company_id', $companyId);

        if ($request->filled('period')) {
            $query->where('period', $request->string('period'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->integer('employee_id'));
        }

        $filenameBase = 'payroll_report_' . now()->format('Ymd_His');

        if ($format === 'pdf') {
            $rows = $query->orderByDesc('payroll_date')->limit(5000)->get();
            return PdfTrait::generatePdf(
                'admin.reports.pdf.payroll',
                $filenameBase,
                [
                    'rows' => $rows,
                    'generatedAt' => now(),
                    'note' => $rows->count() >= 5000 ? 'PDF export is limited to the first 5000 rows. Please use CSV for large exports.' : null,
                ],
                download: true
            );
        }

        $query->orderByDesc('payroll_date');
        return $this->streamCsv(
            $query,
            $filenameBase . '.csv',
            ['ID', 'Employee', 'Period', 'Payroll Date', 'Basic', 'Gross', 'Net', 'Status', 'Approved At'],
            function (Payroll $p) {
                return [
                    $p->id,
                    optional($p->employee)->full_name,
                    $p->period,
                    optional($p->payroll_date)?->format('Y-m-d'),
                    $p->basic_salary,
                    $p->gross_salary,
                    $p->net_salary,
                    $p->status,
                    optional($p->approved_at)?->format('Y-m-d H:i:s'),
                ];
            }
        );
    }

    /**
     * Stream CSV rows without loading everything into memory.
     *
     * @param Builder $query
     * @param string $filename
     * @param array<int, string> $headers
     * @param callable $rowMapper
     */
    private function streamCsv(\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query, string $filename, array $headers, callable $rowMapper): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($query, $headers, $rowMapper) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);

            // Chunk by ID is safer for huge datasets.
            if ($query instanceof \Illuminate\Database\Eloquent\Builder) {
                $query->orderBy('id')->chunkById(5000, function ($rows) use ($out, $rowMapper) {
                    foreach ($rows as $row) {
                        fputcsv($out, $rowMapper($row));
                    }
                });
            } else {
                // Query\Builder doesn't support chunkById() reliably across versions, so use chunk().
                $query->orderBy('id')->chunk(5000, function ($rows) use ($out, $rowMapper) {
                    foreach ($rows as $row) {
                        fputcsv($out, $rowMapper($row));
                    }
                });
            }

            fclose($out);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}

