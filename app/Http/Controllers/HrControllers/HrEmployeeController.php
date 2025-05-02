<?php

namespace App\Http\Controllers\HrControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\EmployeeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrEmployeeController extends Controller
{
    use EmployeeTrait;

        /**
     * Summary of index
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $employees = Auth::user()->company->employees()
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($employees);
        return view('hr.employee.index')
            ->with('employees', $employees);
    }

}
