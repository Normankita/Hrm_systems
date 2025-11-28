@extends('layouts.system')

@section('content')
    @can('view_payroll')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body ">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Payroll List</h3>
                            @can('create_payroll')
                                {{-- <form action="{{ route('employee.manage.payrolls.generateAll') }}" method="POST" class="mb-0"> --}}
                                {{-- @csrf --}}
                                <a href="{{ route('employee.manage.payrolls.getEmployees') }}" class="btn btn-primary">
                                    <i class="mdi mdi-cash-multiple"></i>
                                    Generate Payroll
                                </a>
                                {{-- </form> --}}
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <table class="dt-table table table-bordered table-hover align-middle text-nowrap table-sm">
                                <thead class="table-light text-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Ref#</th>
                                        <th>Payroll Count</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payrolls as $key => $payroll)
                                        <tr class="text-dark">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $payroll->entrence_reference }}</td>
                                            <td>{{ $payroll->payroll_count }}</td>
                                            <td>{{ \Carbon\Carbon::parse($payroll->latest_creation)->format('d M Y') }}</td>
                                            <td>
                                                <x-system.btn-view class="p-0" :route="route(
                                                    'employee.manage.payrolls.singleGroup.show',
                                                    $payroll->entrence_reference,
                                                )" text="View" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
