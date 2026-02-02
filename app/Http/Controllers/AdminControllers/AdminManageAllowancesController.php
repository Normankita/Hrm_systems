<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Allowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminManageAllowancesController extends Controller
{
       /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allowances = Allowance::all()->sortByDesc('created_at');
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
        $companyId = auth()->user()->company_id;
        // allowance name must be unique in within the company
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:allowances,name,company_id,' . $companyId,
            'description' => 'required|string|max:255',
            'is_taxable' => 'required|boolean',
        ]);
        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        $allowance= Allowance::create([
            'company_id' => $companyId,
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
