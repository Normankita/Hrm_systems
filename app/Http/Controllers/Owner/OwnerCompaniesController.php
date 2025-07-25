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
}
