<?php

namespace App\Http\Controllers\AdminControllers;

use App\Enums\TrainingParticipantStatusEnum;
use App\Enums\TrainingStatusEnum;
use App\Enums\TrainingTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Instructor;
use App\Models\Training;
use App\Models\TrainingParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminTrainingController extends Controller
{
    private function trainingRules(?int $trainingId = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'type' => ['required', Rule::in(array_column(TrainingTypeEnum::cases(), 'value'))],
            'status' => ['required', Rule::in(array_column(TrainingStatusEnum::cases(), 'value'))],
            'duration' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id',
            'instructor_ids' => 'nullable|array',
            'instructor_ids.*' => 'exists:instructors,id',
        ];
    }

    public function index(Request $request)
    {
        $query = Training::with(['departments', 'instructors', 'createdBy'])
            ->withCount('participants')
            ->orderByDesc('start_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('instructors', fn ($iq) => $iq->where('name', 'like', '%' . $search . '%'));
            });
        }

        $trainings = $query->get();
        $departments = Department::orderBy('name')->get();
        $registeredInstructors = Instructor::where('is_active', true)->orderBy('name')->get();

        return view('admin.trainings.index', compact('trainings', 'departments', 'registeredInstructors'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), $this->trainingRules())->validate();

        $training = Training::create([
            ...$this->trainingPayload($request),
            'created_by' => Auth::id(),
        ]);

        $this->syncRelations($training, $request);

        return redirect()->route('admin.trainings.index')
            ->with('success', 'Training registered successfully.');
    }

    public function show(Training $training)
    {
        $training->load(['departments', 'instructors', 'createdBy']);
        $training->loadCount('participants');

        $participants = TrainingParticipant::with(['employee.department'])
            ->where('training_id', $training->id)
            ->orderByDesc('enrolled_at')
            ->get();

        $departments = Department::orderBy('name')->get();
        $employees = Employee::orderBy('full_name')->get();
        $registeredInstructors = Instructor::where('is_active', true)->orderBy('name')->get();

        $enrolledEmployeeIds = $participants->pluck('employee_id')->all();

        return view('admin.trainings.show', compact(
            'training',
            'participants',
            'departments',
            'employees',
            'registeredInstructors',
            'enrolledEmployeeIds'
        ));
    }

    public function update(Request $request, Training $training)
    {
        Validator::make($request->all(), $this->trainingRules($training->id))->validate();

        $training->update($this->trainingPayload($request));
        $this->syncRelations($training, $request);

        return redirect()->back()
            ->with('success', 'Training updated successfully.');
    }

    public function destroy(Training $training)
    {
        $training->delete();

        return redirect()->route('admin.trainings.index')
            ->with('success', 'Training deleted successfully.');
    }

    public function enrollByDepartment(Request $request, Training $training)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $employees = Employee::where('department_id', $request->department_id)->get();

        if ($employees->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No employees found in the selected department.');
        }

        $enrolled = 0;
        foreach ($employees as $employee) {
            $created = TrainingParticipant::firstOrCreate(
                [
                    'training_id' => $training->id,
                    'employee_id' => $employee->id,
                ],
                [
                    'department_id' => $request->department_id,
                    'status' => TrainingParticipantStatusEnum::ENROLLED->value,
                    'enrolled_at' => now(),
                ]
            );
            if ($created->wasRecentlyCreated) {
                $enrolled++;
            }
        }

        return redirect()->back()->with(
            'success',
            $enrolled > 0
                ? "{$enrolled} employee(s) enrolled from department."
                : 'All department employees were already enrolled.'
        );
    }

    public function enrollEmployees(Request $request, Training $training)
    {
        $request->validate([
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        $enrolled = 0;
        foreach ($request->employee_ids as $employeeId) {
            $employee = Employee::find($employeeId);
            $created = TrainingParticipant::firstOrCreate(
                [
                    'training_id' => $training->id,
                    'employee_id' => $employeeId,
                ],
                [
                    'department_id' => $employee?->department_id,
                    'status' => TrainingParticipantStatusEnum::ENROLLED->value,
                    'enrolled_at' => now(),
                ]
            );
            if ($created->wasRecentlyCreated) {
                $enrolled++;
            }
        }

        return redirect()->back()->with(
            'success',
            $enrolled > 0
                ? "{$enrolled} employee(s) enrolled successfully."
                : 'Selected employees were already enrolled.'
        );
    }

    public function updateParticipant(Request $request, Training $training, TrainingParticipant $participant)
    {
        if ($participant->training_id !== $training->id) {
            abort(404);
        }

        $request->validate([
            'status' => ['required', Rule::in(array_column(TrainingParticipantStatusEnum::cases(), 'value'))],
            'notes' => 'nullable|string|max:500',
        ]);

        $data = [
            'status' => $request->status,
            'notes' => $request->notes,
        ];

        if ($request->status === TrainingParticipantStatusEnum::COMPLETED->value) {
            $data['completed_at'] = now();
        } else {
            $data['completed_at'] = null;
        }

        $participant->update($data);

        return redirect()->back()->with('success', 'Participant updated successfully.');
    }

    public function removeParticipant(Training $training, TrainingParticipant $participant)
    {
        if ($participant->training_id !== $training->id) {
            abort(404);
        }

        $participant->delete();

        return redirect()->back()->with('success', 'Participant removed from training.');
    }

    private function trainingPayload(Request $request): array
    {
        return [
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'status' => $request->status,
            'duration' => $request->duration,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?: null,
        ];
    }

    private function syncRelations(Training $training, Request $request): void
    {
        $training->departments()->sync($request->input('department_ids', []));
        $training->instructors()->sync($request->input('instructor_ids', []));
    }
}
