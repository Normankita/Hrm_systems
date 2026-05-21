<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;

class AdminEmployeeRelationsController extends Controller
{
    public function index()
    {
        return view('admin.employee-relations.index');
    }
}
