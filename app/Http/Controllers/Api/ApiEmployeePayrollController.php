<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\PayrollService;
use App\Models\Event;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiEmployeePayrollController extends Controller
{
    public function generateForSelected(Request $request)
    {
        $employeeIds = $request->input('selected_employees');
        if (!$employeeIds) {
            return response()->json([
                'status' => 'error',
                'message' => 'No employees selected.'
            ]);
        }
        $response = PayrollService::generatePayrollForSelectedEmployees(
            false,
            $employeeIds
        );
        if ($response['status'] === 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $response['message'],
                'error' => $response['error'] ?? 'Unknown error'
            ]);
        }
        $payrolls = $response['data'];
        return response()->json([
            'status' => 'success',
            'message' => count($payrolls) . ' payrolls generated.',
            'data' => $payrolls
        ]);
    }


    public function approveSelected(Request $request)
    {
        $selected_payrolls = $request->input('selected_payrolls');
        if ($selected_payrolls === null || !is_array($selected_payrolls) || count($selected_payrolls) === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No payrolls selected for approval.'
            ]);
        }
        DB::beginTransaction();
        try {
            $queryBuilder = Payroll::where('status', 'pending')
                ->whereIn('id', $selected_payrolls);
            $queryBuilder->update([
                'status' => 'approved'
            ]);
            $queryBuilder->each(function ($payroll) {
                $payroll->recordEvent('update', ['status' => 'approved']);
            });
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Selected pending payrolls approved successfully.'
        ]);
    }

}
