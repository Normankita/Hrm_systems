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
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'brela_reg_number' => 'required|string|max:50',
            'tin_number' => 'required|string|max:50',
        ]);

        // Fetch the company from the database
        $company = Company::find($id);

        // Check if the company exists
        if (!$company) {
            return redirect()->back()->with('error', 'Company not found.');
        }

        // Update the company data
        $company->update($request->all());

        // Redirect back with a success message
        return redirect()->route('admin.companies.edit', $id)->with('success', 'Company details updated successfully.');
    }
}
