<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class AdminDepartmentController extends Controller
{
    public function index () {
        $departments = Department::where('company_id',
            Auth::user()->company_id)->get();
        $company = Auth::user()->company;
        return view('admin.departments.index')
            ->with('departments', $departments)
            ->with('company', $company);
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ];
        $this->validate($request, $rules);

    }
}
