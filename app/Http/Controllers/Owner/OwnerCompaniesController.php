<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Services\CompanyService;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OwnerCompaniesController extends Controller
{
    public function companiesAll()
    {
        $companies = Company::ownerCompanies()->get();
        return view('owner.companies.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $response = CompanyService::createCompany($request->all());
        if ($response['status'] === 'error') {
            if ($response['type'] == 'validation') {
                return redirect()->back()
                    ->withErrors($response['validated'])
                    ->withInput();
            }
            return redirect()->back()
                ->with('error', $response['message'])
                ->withInput();
        }
        return redirect()->route('owner.companies.all')
            ->with('success', 'Company created successfully');
    }


    public function show($id)
    {
        $company = Company::find($id);
        return view('owner.companies.show')
            ->with('company', $company);
    }

    public function addAdmin(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'name' => 'required|string',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'company_id' => 'required|exists:companies,id',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        $validated = Validator::make($request->all(), $rules);
        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        $response = CompanyService::addAdminToCompany($request->all());
        if ($response['status'] === 'error') {
            return redirect()->back()
                ->with('error', $response['message'])
                ->withInput();
        }
        return redirect()->back()->with('success',
         "$response[message]");

    }
}
