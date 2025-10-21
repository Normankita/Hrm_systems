<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Allowance;
use Illuminate\Http\Request;

class AdminManageAllowancesController extends Controller
{
       /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allowances = Allowance::all();
        return view("admin.allowances.index",
            compact("allowances"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.allowances.create');
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
        return redirect()->route('admin.allowances.index')
            ->with('success', 'Allowance created successfully');
    }
    public function edit(Request $request, Allowance $allowance) {
        return redirect()->back();
    }
}
