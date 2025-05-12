<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/users', function () {
    return response()->json(User::all());
})->name('api.roles.index');
