<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contribution;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Validator;

class AdminCompanyController extends Controller
{
    public function edit($id)
    {
        // Fetch the company data from the database using the provided ID
        $company = Company::find($id);
        // Render deductions too
        $contributions = Contribution::where(
            "company_id", $company->id)->get();
        // Check if the company exists
        if (!$company) {
            return redirect()->back()->with(
                'error', 'Company not found.');
        }
        // Return the edit view with the company data
        return view('admin.companies.edit', compact(
            'company', 'contributions'));
    }


    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'brela_reg_number' => 'required|string|max:50',
            'tin_number' => 'required|string|max:50',
            'contributions' => 'required|array',
            'contributions.*.percent' => 'required|numeric|min:0|max:100',
            'contributions.*.description' => 'required|string|max:255'
        ]);
        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        // filtering the settings fields from the form field
        $formFields = collect($request->all());
        $settings = $formFields->filter(function ($value, $key) {
            return str_starts_with($key, 'skysetlist-');
        })->mapWithKeys(function ($value, $key) {
            return [str_replace('skysetlist-', '', $key) => $value];
        });

        $company = Company::find($id);
        if (!$company) {
            return redirect()->back()->with(
                'error', 'Company not found.');
        }

        DB::beginTransaction();
        try {
            $company->update([
                'name' => $request->name,
                'address' => $request->address,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'brela_reg_number' => $request->brela_reg_number,
                'tin_number' => $request->tin_number,
            ]);
            foreach ($request->input('contributions', []) as $contributionId => $data) {
                // if array data doesnt has employee_percent or company_percent, skip it
                if (!isset($data['employee_percent']) || !isset($data['company_percent'])) {
                    $data['employee_percent'] = $data['percent'];
                    $data['company_percent'] = 0;
                }
                $totalPercent = $data['employee_percent'] + $data['company_percent'];
                if ($totalPercent != $data['percent']) {
                    DB::rollBack();
                    return redirect()->route('admin.companies.edit', $company->id)
                        ->with('fail', 'The Company and Employee total percent for ' . Contribution::find($contributionId)->name . ' must '.$data['percent']. '%. Currently, it is '.$totalPercent.'%. Please correct it and try again.')
                        ->withInput();
                }
                Contribution::where('id', $contributionId)->update([
                    'percent' => $data['percent'],
                    'description' => $data['description'],
                    'employee_percent' => $data['employee_percent'],
                    'company_percent' => $data['company_percent'],
                ]);
            }

            // update the settings, dont delete them, cause they are being used by the system
            foreach ($settings as $name => $value) {
                if ($name == "payment_date" && (empty($value) || $value > 29 || $value < 1)) {
                    continue; // Skip if payment_date is empty
                }
                Setting::updateOrCreate(
                    ['name' => $name, 'company_id' => $company->id],
                    ['value' => $value]
                );
            }
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            return redirect()->route('admin.companies.edit', $company->id)
                ->with('fail', 'An error occurred while updating company details: ' . $throwable->getMessage());
        }
        session()->flash('status', 'success');
        return redirect()->route('admin.companies.edit', $company->id)
            ->with(['status' => 'success', 'message' => 'Company details updated successfully.']);
    }

}
