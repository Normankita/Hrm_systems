<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSettingController extends Controller
{
    public function edit($id) {

    }

    public function index() {

        $settings = Auth::user()->company->settings;
        return view('admin.settings.index')
            ->with('settings', $settings);
    }
 public function store(Request $request){
    $request->validate( [
        'name' => 'required',
        'value'=>'required',
    ]);
    Setting::create($request->all());
    return redirect()->route('admin.settings.index')->with('success','Setting created successfully');
 }
 public function update(Request $request, $id) {
    $request->validate( [
        'name' => 'required',
        'value'=>'required',
    ]);
    Setting::where('id', $id)->update($request->only(['name','value']));
    return redirect()->route('admin.settings.index')->with('success','Setting updated successfully');
 }
}
