<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class AdminCompanyController extends Controller
{
    public function edit($id) {
        // Fetch the company data from the database using the provided ID
        $company = Company::find($id);
        // Check if the company exists
        if (!$company) {
            return redirect()->back()->with('error', 'Company not found.');
        }
        // Return the edit view with the company data
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, $id) {
        dd($request->all());
    }
}
