<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Allowance;
use Illuminate\Support\Facades\Validator;

class ApiManageAllowanceController extends Controller
{
    /**
     * api function to allow user to edit allowance name and description
     * @param Request $request
     * @param Allowance $allowance
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Allowance $allowance)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        if ($validated->fails()) {
            return response()->json(['status' => 'fail', 'message' => $validated->errors()], 400);
        }
        $allowance->update($request->all());
        return response()->json(['status' => 'success', 
        'message' => 'Allowance updated successfully', 'data' => $allowance]);
    }
}
