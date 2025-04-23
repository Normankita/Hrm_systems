<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDepartmentController extends Controller
{
    public function index () {
        return view('admin.departments.index');
    }
}
