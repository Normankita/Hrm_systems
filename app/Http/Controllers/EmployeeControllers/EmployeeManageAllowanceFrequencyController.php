<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\FrequencyTrait;
use App\Models\AllowanceFrequency;
use Illuminate\Http\Request;

class EmployeeManageAllowanceFrequencyController extends Controller
{
    use FrequencyTrait;
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
            'no_base_times'=>'required',
        ];
        $request->validate($rules);
        $outcome= $this->createFrequency($request);
        if( $outcome['status']=='fail' ){
            return redirect()->back()->with($outcome);
        }
        return redirect()->back()->with('success','frequency created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */

}
