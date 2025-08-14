<?php

namespace App\Http\Controllers\OwnerControllers;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Attendance;

class OwnerUsersController extends Controller {
    public function index(UsersDataTable $usersDataTable) {
        return $usersDataTable->render('owner.users.index');
    }
}
