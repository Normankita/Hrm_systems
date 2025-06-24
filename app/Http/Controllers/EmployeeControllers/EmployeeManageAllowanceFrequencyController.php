<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Models\AllowanceFrequency;
use Illuminate\Http\Request;

class EmployeeManageAllowanceFrequencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $frequencies = AllowanceFrequency::all();
        return view('employee.manage.allowances.frequencies', compact('frequencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'base_category' => 'required',
            'no_times' => 'required',
        ];
        $request->validate($rules);
        if ($request->input('base_category') == 'month') {
            $number_days = 30 / $request->input('no_times');
            $request->merge([
                'days_apart' => $number_days
            ]);
        } else {
            $number_days = 365 / $request->input('no_times');
            $request->merge([
                'days_apart' => $number_days
            ]);
        }
        AllowanceFrequency::create($request->all());
        return redirect()->back()->with('success','frequency created successfully');
    }
    /**
     * Show the form for editing the specified resource.
     */

}
