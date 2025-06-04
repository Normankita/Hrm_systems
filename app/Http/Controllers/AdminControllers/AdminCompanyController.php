<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminCompanyController extends Controller
{
    public function edit($id)
    {
        // Fetch the company data from the database using the provided ID
        $company = Company::find($id);
        // Render deductions too
        $contributions = Contribution::where("company_id", $company->id)->get();
        // Check if the company exists
        if (!$company) {
            return redirect()->back()->with('error', 'Company not found.');
        }
        // Return the edit view with the company data
        return view('admin.companies.edit', compact('company', 'contributions'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'brela_reg_number' => 'required|string|max:50',
            'tin_number' => 'required|string|max:50',
            'contributions' => 'required|array',
            'contributions.*.percent' => 'required|numeric|min:0|max:100',
            'contributions.*.description' => 'required|string|max:255',
        ]);

        $company = Company::find($id);
        if (!$company) {
            return redirect()->back()->with('error', 'Company not found.');
        }

        DB::transaction(function () use ($request, $company) {
            $company->update([
                'name' => $request->name,
                'address' => $request->address,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'brela_reg_number' => $request->brela_reg_number,
                'tin_number' => $request->tin_number,
            ]);

            foreach ($request->input('contributions', []) as $contributionId => $data) {
                Contribution::where('id', $contributionId)->update([
                    'percent' => $data['percent'],
                    'description' => $data['description'],
                ]);
            }
        });

        return redirect()->route('admin.companies.edit', $company->id)
            ->with('success', 'Company details updated successfully.');
    }

}
