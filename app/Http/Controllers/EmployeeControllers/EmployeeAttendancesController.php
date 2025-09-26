<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeAttendancesController extends Controller
{
    public function dashboard() {
        return  view('employee.attendance.dashboard');
    }
}
