<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Utils\Traits\UploadFileTrait;

class LeaveController extends Controller
{
    use UploadFileTrait;

    /**
     * Display a listing of the leaves.
     */
    public function index()
    {
        return Leave::with('attachments')->get();
    }

    /**
     * Store a newly created leave with attachments.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $leave = Leave::create($request->only(['employee_id', 'reason', 'start_date', 'end_date']));

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $this->handleDocumentUpload(
                    $file,
                    'leave_attachment',
                    $attachments,
                    'attachments/leaves'
                );
            }

            foreach ($attachments as $attachment) {
                $leave->attachments()->create([
                    'filename' => $attachment['filename'],
                    'path' => $attachment['path'],
                ]);
            }
        }

        return response()->json($leave->load('attachments'), 201);
    }

    /**
     * Display the specified leave with attachments.
     */
    public function show($id)
    {
        $leave = Leave::with('attachments')->findOrFail($id);
        return response()->json($leave);
    }

    /**
     * Remove the specified leave and its attachments.
     */
    public function destroy($id)
    {
        $leave = Leave::with('attachments')->findOrFail($id);

        // Delete attached files from storage
        foreach ($leave->attachments as $attachment) {
            $this->deleteFile($attachment->path);
            $attachment->delete();
        }

        $leave->delete();

        return response()->json(['message' => 'Leave and attachments deleted successfully.']);
    }
}