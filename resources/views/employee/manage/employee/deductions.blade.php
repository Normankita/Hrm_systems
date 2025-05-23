@extends('layouts.system')

@section('content')
    @canany(['edit_paygrade', 'edit_deductions', 'view_deductions', 'create_deductions'])
        <div class="row">
            <div class="card">
                <div class="card-body">
                   {{-- Create deduction  --}}
                   @can('create_deductions')
                        <x-system.modal-button id="createDeductionModal" form="createDeductionForm" title="Create Deduction" text="Create a Deduction"/>
                    <x-system.modal id="createDeductionModal" form="createDeductionForm" title="Create Deduction" :inside="true">
                        <form action="{{ route('employee.manage.deductions.store', $employee->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label">Deduction Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="total_amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="installments" class="form-label">Installments</label>
                                <input type="number" name="installments" id="installments" class="form-control" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description">
                                    <span class="text-muted">Description</span>
                                    <textarea name="description" id="description" class="form-control"></textarea>
                                </label>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">Add Deduction</button>
                            </div>
                        </div>
                    </form>
                    </x-system.modal>
                   @endcan
                    {{-- Existing Deductions Table --}}
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Deduction</th>
                                    <th>Amount</th>
                                    <th>Cycles</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employee->deductions as $deduction)
                                    <tr>
                                        <td>{{ $deduction->name }}</td>
                                        <td>{{ number_format($deduction->total_amount, 2) }}</td>
                                        <td>{{ $deduction->installments }}</td>
                                        <td>
                                            @can('delete_deductions')
                                                <form
                                                    action="{{ route('employee.manage.deductions.destroy', [$employee->id, $deduction->id]) }}"
                                                    method="POST" onsubmit="return confirm('Delete this deduction?')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger btn-sm p-1 mx-1 mdi mdi-trash">Delete</button>
                                                </form>
                                            @endcan

                                            <!-- Edit button -->
                                            @can('edit_deductions')
                                                <x-system.modal-button class="btn btn-outline-dark btn-sm p-1 m-1 mdi mdi-pencil"
                                                    id="editDeductionModal-{{ $deduction->id }}" data-bs-toggle="modal"
                                                    text="Edit" textColor="" />
                                            @endcan
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No deductions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @can('edit_deductions')
                            @foreach ($employee->deductions as $deduction)
                                <x-system.modal id="editDeductionModal-{{ $deduction->id }}"
                                    form="editDeductionForm-{{ $deduction->id }}" title="Edit Deduction - {{ $deduction->name }}"
                                    size="md" :inside="true">
                                    <form action="{{ route('employee.manage.deductions.update', [$employee->id, $deduction->id]) }}"
                                        method="POST" id="editDeductionForm-{{ $deduction->id }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-3">
                                            <label for="name-{{ $deduction->id }}" class="form-label">Deduction
                                                Name</label>
                                            <input type="text" name="name" id="name-{{ $deduction->id }}"
                                                class="form-control" value="{{ $deduction->name }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="total_amount-{{ $deduction->id }}" class="form-label">Total
                                                Amount</label>
                                            <input type="number" step="0.01" name="total_amount"
                                                id="total_amount-{{ $deduction->id }}" class="form-control"
                                                value="{{ $deduction->total_amount }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="installments-{{ $deduction->id }}" class="form-label">Installments</label>
                                            <input type="number" name="installments" id="installments-{{ $deduction->id }}"
                                                class="form-control" value="{{ $deduction->installments }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description">
                                                <span class="text-muted">Description</span>
                                                <textarea name="description" id="description" class="form-control">{{ $deduction->description }}</textarea>
                                            </label>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Update Deduction</button>
                                        </div>
                                    </form>
                                </x-system.modal>
                            @endforeach
                        @endcan

                    {{-- Back Button --}}
                    <div class="mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary "
                            style="text-decoration: none; border: 1px dashed">← Back </a>

                    </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    @endcanany
@endsection

