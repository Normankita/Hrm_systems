<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Utils\Traits\FrequencyTrait;
use App\Models\AllowanceFrequency;
use Illuminate\Http\Request;

class ApiAllowanceFrequencyController extends Controller
{
    use FrequencyTrait;
    public function updateFrequency(Request $request)
    {
        // Validate the incoming request data
        $rules = [
            'id' => 'required',
            'name' => 'required|string|max:255',
            'base_category' => 'required|string|in:year,month,week',
            'no_times' => 'required|integer|min:1',
            'no_base_times' => 'required|integer|min:1',
        ];
        $id = $request->input('id');
        $validatedData = $request->validate($rules);

        // Find the allowance frequency by ID
        $frequency = AllowanceFrequency::find($id);
        if (!$frequency) {
            return response()->json(['status' => 'fail', 'message' => 'Allowance frequency not found'], 404);
        }
        $responce = $this->updateFrequencyService($request, $id);
        // Update the frequency with validated data
        $frequency->update($validatedData);

        return response()->json(['status' => 'success',
        'message' => 'Allowance frequency updated successfully', 'data' => $frequency]);
    }
}
