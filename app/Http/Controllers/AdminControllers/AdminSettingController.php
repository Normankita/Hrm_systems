<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\SettingsTrait;
use App\Models\Setting;
use App\Models\SettingOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSettingController extends Controller
{
    public function edit($id)
    {

    }

    public function index()
    {
        $settings = Auth::user()->company->settings;
        $options = SettingOptions::all();
        $settings = $settings->map(function ($setting) use ($options) {
            $options = SettingsTrait::settingOptions($setting->id);
            $setting->options = $options;
            return $setting;
        });
        return view('admin.settings.index')
            ->with('settings', $settings);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'value' => 'required',
        ]);
        Setting::create($request->all());
        return redirect()->route('admin.settings.index')->with('success', 'Setting created successfully');
    }


    public function update(Request $request, $id)
    {
        $payments_date = Setting::find($id);
        if ($payments_date->name == "payment_date") {
            $value = $request->value;
            if ($value > 29 || $value < 1) {
                return redirect()->route('admin.settings.index')
                    ->with('fail', 'Invalid payment date');
            }
        }
        $request->validate([
            'value' => 'required',
        ]);
        $setting = Setting::where('id', $id)
            ->first();
        $setting->update(['value' => $request->value]);
        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting updated successfully');
    }
}
