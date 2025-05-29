@extends('layouts.system')

@section('content')
<div class="col-sm-12 col-md-12">
    <div>
        <form action="{{ route('admin.employees.excel.import') }}"
             method="POST" enctype="multipart/form-data">
            <div>
                @csrf
                <div class="form-group">
                    <label for="file">Import Employees from Excel</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                    @error('file')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>
        </form>
    </div>
    <x-system.forms.create-employee-form route="admin.employees.store"
     />
</div>
@endsection
