<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\UploadFileTrait;
use App\Models\Leave;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class EmployeeLeaveController extends Controller
{
    use UploadFileTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = Leave::with(['employee', 'leaveType', 'attachments'])
            ->where('employee_id', auth()->user()->employee->id)
            ->get();

        return view('employee.leave.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaveTypes = LeaveType::all();

        return view('employee.leave.request', compact('leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validateLeaveRequest($request);

        $leave = Leave::create($this->prepareLeaveData($request));

        $this->handleAttachments($request, $leave);

        return redirect()->route('employees.leave.status')->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $leave = Leave::with('attachments')->findOrFail($id);

        return view('employee.leave.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $leave = Leave::with('attachments')->findOrFail($id);
        $leaveTypes = LeaveType::all();

        return view('employee.leave.edit', compact('leave', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leave $leave)
    {
        $this->validateLeaveRequest($request);

        // Delete existing attachments if new ones are provided
        if ($request->hasFile('attachments')) {
            $this->deleteExistingAttachments($leave);
        }

        $leave->update($this->prepareLeaveData($request));

        $this->handleAttachments($request, $leave);

        return redirect()->route('employees.leave.status')->with('success', 'Leave request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $leave = Leave::with('attachments')->findOrFail($id);

        $this->deleteExistingAttachments($leave);

        $leave->delete();

        return redirect()->route('employees.leave.status')->with('success', 'Leave request canceled successfully.');
    }

    /**
     * Validate the leave request.
     */
    private function validateLeaveRequest(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
    }

    /**
     * Prepare leave data for storing or updating.
     */
    private function prepareLeaveData(Request $request)
    {
        return [
            'employee_id' => auth()->user()->employee->id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending', // Assuming 'pending' is the default status
        ];
    }

    /**
     * Handle file attachments for a leave request.
     */
    private function handleAttachments(Request $request, Leave $leave)
    {
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $this->handleDocumentUpload(
                    $file,
                    'leave_attachment',
                    $attachments,
                    'attachments/leaves'
                );
            }

            foreach ($attachments as $attachment) {
                $leave->attachments()->create($attachment);
            }
        }
    }

    /**
     * Delete existing attachments for a leave request.
     */
    private function deleteExistingAttachments(Leave $leave)
    {
        foreach ($leave->attachments as $attachment) {
            $this->deleteFile($attachment->path);
            $attachment->delete();
        }
    }
}
