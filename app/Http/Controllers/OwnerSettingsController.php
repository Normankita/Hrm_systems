<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerSettingsController extends Controller
{
    public function index()
    {
        return view('owner.settings.index');
    }
}
