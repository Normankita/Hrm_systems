@extends('layouts.system')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.employees.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="mb-5">
                                    <label class="text-dark font-weight-medium" for="">Date input</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text mdi mdi-calendar" id="basic-addon1"></span>
                                        </div>
                                        <input type="text" class="form-control" data-mask="00/00/0000">
                                    </div>
                                    <p style="font-size: 90%">ex. 99/99/9999</p>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="mb-5">
                                    <label class="text-dark font-weight-medium">Phone input</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text mdi mdi-phone" id="basic-addon1"></span>
                                        </div>
                                        <input type="text" class="form-control" data-mask="(999) 999-9999">
                                    </div>
                                    <p style="font-size: 90%">ex. (999) 999-9999</p>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="mb-5">
                                    <label class="text-dark font-weight-medium">Taxpayer Identification Numbers</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text mdi mdi-currency-usd" id="basic-addon1"></span>
                                        </div>
                                        <input type="text" class="form-control" data-mask="99-9999999">
                                    </div>
                                    <p style="font-size: 90%">ex. 99-9999999</p>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="mb-5">
                                    <label class="text-dark font-weight-medium">Social Security Number</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text mdi mdi-server-security" id="basic-addon1"></span>
                                        </div>
                                        <input type="text" class="form-control" data-mask="999-99-9999">
                                    </div>
                                    <p style="font-size: 90%">ex. 999-99-9999</p>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="mb-5">
                                    <label class="text-dark font-weight-medium">Eye Script</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text mdi mdi-settings" id="basic-addon1"></span>
                                        </div>
                                        <input type="text" class="form-control" data-mask="~9.99 ~9.99 999">
                                    </div>
                                    <p style="font-size: 90%">ex. ~9.99 ~9.99 999</p>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="mb-5">
                                    <label class="text-dark font-weight-medium">Credit Card Number</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text mdi mdi-credit-card" id="basic-addon1"></span>
                                        </div>
                                        <input type="text" class="form-control" data-mask="9999 9999 9999 9999">
                                    </div>
                                    <p style="font-size: 90%">ex. 9999 9999 9999 9999</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
