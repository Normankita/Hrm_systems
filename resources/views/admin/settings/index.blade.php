@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body ">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Employee Directory</h3>
                        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">Add Employee</a>
                    </div>

                    <div class="table-responsive">
                        <span>Company Settings</span>
                        <x-system.table>
                            <x-slot name="head">
                                <x-system.table-head>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Value</th>
                                    </tr>
                                </x-system.table-head>
                            </x-slot>

                            <x-slot name="body">
                                <x-system.table-body>
                                    @foreach ($settings as $key => $setting)
                                        <tr>
                                            <td>{{ $setting->name }}</td>
                                            <td>{{ $setting->value }}</td>
                                            <x-system.btn-edit></x-system.btn-edit>
                                        </tr>
                                    @endforeach
                                </x-system.table-body>
                            </x-slot>
                        </x-system.table>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
