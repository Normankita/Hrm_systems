<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminAttendanceSessionsController extends Controller
{

    /**
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $sessions = AttendanceSession::orderBy('id', 'desc')->get();
        return view('admin.attendance.sessions.index', [
            'sessions' => $sessions
        ]);
    }


    public function store(Request $request)
    {
        $rules = [
            'type' => [
                'required',
                'string',
                Rule::unique('attendance_sessions', 'session_type') // Replace 'sessions' with your table name
                    ->where(function ($query) {
                        $query->where('company_id', auth()->user()->company_id);
                    }),
            ],
            'start' => [
                'required',
            ],
            'end' => [
                'required',
            ],
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return redirect()->back()
            ->with('error', 'Fail to create session')
            ->withErrors($validate)->withInput();
        }

        $session = AttendanceSession::create([
            'company_id' => auth()->user()->company_id,
            'session_type' => $request->type,
            'start_time' => $request->start,
            'end_time' => $request->end,
            'is_active' => true, // Default to active
        ]);
        if (!$session) {
            return redirect()->back()->with('error',
             'Failed to create session');
        }
        return redirect()->back()->with('success', 'Session created successfully');
    }


    /**
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $session = AttendanceSession::find($id);
        if (!$session) {
            return redirect()->back()->with('error', 'Session not found');
        }
        $rules = [
            'type' => [
                'required',
                'string',
                Rule::unique('attendance_sessions', 'session_type')
                    ->ignore($session->id)
                    ->where(function ($query) {
                        $query->where('company_id', auth()->user()->company_id);
                    }),
            ],
            'start' => [
                'required',
            ],
            'end' => [
                'required',
            ],
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return redirect()->back()
            ->with('error', 'Validation failed')
            ->withErrors($validate)->withInput();
        }
        $session->update([
            'session_type' => $request->type,
            'start_time' => $request->start,
            'end_time' => $request->end,
        ]);

        return redirect()->back()->with('success', 'Session updated successfully');
    }


    public function getSessionDashboard()
    {
        $attendanceRecords = AttendanceRecord::with('employee',
         'attendanceSession')
            ->orderBy('date', 'desc')
            ->get();
        return view('admin.attendance.sessions.dashboard', [
            'attendanceRecords' => $attendanceRecords
        ]);
    }
}
