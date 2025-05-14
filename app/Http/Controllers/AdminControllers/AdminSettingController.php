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
}
