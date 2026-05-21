<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminInstructorController extends Controller
{
    private function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'specialization' => 'nullable|string|max:255',
            'employee_id' => 'nullable|exists:employees,id',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    public function index()
    {
        $instructors = Instructor::with('employee')
            ->withCount('trainings')
            ->orderBy('name')
            ->get();

        $employees = Employee::orderBy('full_name')->get();

        return view('admin.instructors.index', compact('instructors', 'employees'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), $this->rules())->validate();

        Instructor::create($this->payload($request));

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Instructor registered successfully.');
    }

    public function update(Request $request, Instructor $instructor)
    {
        Validator::make($request->all(), $this->rules())->validate();

        $instructor->update($this->payload($request));

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Instructor updated successfully.');
    }

    public function destroy(Instructor $instructor)
    {
        if ($instructor->trainings()->exists()) {
            return redirect()->route('admin.instructors.index')
                ->with('error', 'Cannot delete instructor assigned to trainings. Deactivate instead.');
        }

        $instructor->delete();

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Instructor deleted successfully.');
    }

    private function payload(Request $request): array
    {
        return [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'employee_id' => $request->employee_id ?: null,
            'is_active' => $request->boolean('is_active', true),
            'notes' => $request->notes,
        ];
    }
}
