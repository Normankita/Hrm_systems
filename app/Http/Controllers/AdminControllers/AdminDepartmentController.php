<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AdminDepartmentController extends Controller
{
    public function index () {
        $departments = Department::where('company_id',
            Auth::user()->company_id)
            ->orderBy('created_at', 'desc')
            ->get();
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
        Validator::make($request->all(), $rules)->validate();
        $department = new Department();
        $department->company_id = Auth::user()->company_id;
        $department->name = $request->input('name');
        $department->code = $request->input('code');
        $department->description = $request->input('description');
        $department->save();
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully');

    }

    public function update(Request $request, $id) {
        $department = Department::find($id);
        if (!$department) {
            return redirect()->route('admin.departments.index')
                ->with('error', 'Department not found');
        }
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ];
        Validator::make($request->all(), $rules)->validate();
        $department->name = $request->input('name');
        $department->code = $request->input('code');
        $department->description = $request->input('description');
        $department->save();
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully');
    }
}
