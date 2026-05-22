<?php

namespace App\Http\Services;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function updatePassword(Request $request, $id) {
        $user = User::find($id);
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            return [
                'status' => 'success',
                'message' => 'Password updated successfully'
            ];
        }else {
            return [
                'status' => 'fail',
                'message' => 'User not found'
            ];
        }

    }
}
