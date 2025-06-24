<?php

namespace App\Http\Controllers\EmployeeControllers;

use App\Http\Controllers\Controller;
use App\Models\Allowance;
use Illuminate\Http\Request;

class EmployeeManageAllowancesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allowances = Allowance::all();
        return view("employee.manage.allowances.index", compact("allowances"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.manage.allowances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'is_taxable' => 'required|boolean',
        ]);

        $allowance= Allowance::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'description' => $request->description,
            'is_taxable' => $request->is_taxable
        ]);
        $allowance->recordEvent('add', $allowance->toArray());
        return redirect()->route('employee.manage.allowances.index')->with('success', 'Allowance created successfully');
    }
}
