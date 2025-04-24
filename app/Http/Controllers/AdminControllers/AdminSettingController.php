<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function edit($id) {
        $settings = Setting::find($id)
            ->first();
        dd($settings);
    }
}
