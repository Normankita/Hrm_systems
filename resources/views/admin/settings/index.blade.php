@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body ">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Employee Directory</h3>
                        <x-system.modal-button class="btn btn-primary mb-3"
                        id="CreateSetting" text="Add New Setting" />

                    <x-system.modal size="modal-lg" id="CreateSetting" title="Create New Setting"
                        form="CreateSettingForm">
                        <form id="CreateSettingForm" action="{{ route('admin.settings.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Setting Name</label>
                                    <input type="text" value="{{ old('name') }}" class="form-control" name="name"
                                        id="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="value">value</label>
                                    <textarea class="form-control" name="value">
                                        {{ old('value') }}</textarea>
                                </div>
                            </div>
                        </form>
                    </x-system.modal>

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
                                        <th>Action</th>
                                    </tr>
                                </x-system.table-head>
                            </x-slot>

                            <x-slot name="body">
                                <x-system.table-body>
                                    @foreach ($settings as $key => $setting)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $setting->name }}</td>
                                            <td>{{ $setting->value }}</td>
                                            <td class="d-flex p-1 gap-1">

                                            {{-- Edit Button --}}
                                            <x-system.modal-button class="btn btn-outline-dark btn-sm p-1 mx-1 mdi mdi-pencil"
                                                id="editSetting{{ $setting->id }}" text="Edit" textColor="" />
                                            </td>
                                        </tr>

                                    <x-system.modal size="modal-lg" id="editSetting{{ $setting->id }}" title="Edit Pay Grade"
                                        form="editSettingForm{{ $setting->id }}">
                                        <form id="editSettingForm{{ $setting->id }}"
                                            action="{{ route('admin.settings.update', $setting->id) }}" method="POST">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body">
                                                {{-- <div class="form-group">
                                                    <label for="name">Setting Name</label>
                                                    <input type="text" value="{{ $setting->name }}" class="form-control"
                                                        name="name" required>
                                                </div> --}}
                                                <div class="form-group">
                                                    <label for="value">value</label>
                                                    <textarea class="form-control"
                                                        name="value">{{ $setting->value }}</textarea>
                                                </div>
                                            </div>
                                        </form>
                                    </x-system.modal>
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
