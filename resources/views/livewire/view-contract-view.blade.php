<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="fw-bold mb-2">
                    <i class="bi bi-file-earmark-text"></i>
                    Contract Details
                </h2>

                <p class="mb-0">
                    Contract Number:
                    <strong>{{ $contract->contract_number }}</strong>
                </p>
            </div>

            <div class="mt-3 mt-md-0">
                @if ($contract->contract_status == 'active')
                    <span class="badge bg-success badge-status">
                        Active Contract
                    </span>
                @else
                    <span class="badge bg-danger badge-status">
                        Inactive Contract
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- Employee Information -->
        <div class="col-lg-4">
            <div class="card info-card h-100">
                <div class="card-body">

                    <div class="section-title">
                        <i class="bi bi-person-badge"></i>
                        Employee Information
                    </div>

                    <div class=" align-items-center mb-4">
                        <div class="avatar me-3">
                            <img src="{{ $contract->employee->profile_picture
                                ? asset('storage/' . $contract->employee->profile_picture)
                                : 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg' }}"
                                alt="Profile Image" class="profile-img me-3"" alt="Employee Profile Picture"
                                class="img-fluid rounded-circle" width="100" height="100">
                        </div>

                        <div>
                            <h5 class="mb-1">{{ $contract->employee->full_name }}</h5>
                            <div class="text-muted">
                                @if ($contract->employee->designation)
                                    {{ $contract->employee->designation->name }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="info-title">Department</div>
                        <div class="info-value">{{ $contract->employee->department->name }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="info-title">Designation</div>
                        <div class="info-value">
                            @if ($contract->employee->designation)
                                {{ $contract->employee->designation->name }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="info-title">Work Location</div>
                        <div class="info-value">{{ $contract->work_location }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="info-title">Company</div>
                        <div class="info-value">{{ $contract->employee->company->name }}</div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Contract Details -->
        <div class="col-lg-8">
            <div class="card info-card">
                <div class="card-body">

                    <div class="section-title">
                        <i class="bi bi-file-earmark-richtext"></i>
                        Contract Information
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-4">
                            <div class="info-title">Contract Type</div>
                            <div class="info-value">{{ $contract->contract_type }}</div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-title">Payment Frequency</div>
                            <div class="info-value">{{ $contract->payment_frequency }}</div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-title">Start Date</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($contract->start_date)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-title">End Date</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($contract->end_date)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-title">Probation End Date</div>
                            <div class="info-value">
                                @if ($contract->probation_end_date)
                                    {{ \Carbon\Carbon::parse($contract->probation_end_date)->format('d M Y') }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-title">Signed Date</div>
                            <div class="info-value">
                                @if ($contract->signed_date)
                                    {{ \Carbon\Carbon::parse($contract->signed_date)->format('d M Y') }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-title">Basic Salary</div>
                            <div class="info-value">
                                {{ format_currency($contract->employee->getBaseSalary() ?? 0, $contract->employee->currency ?? 'TZS') }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-title">Currency</div>
                            <div class="info-value">{{ $contract->currency ?? 'TZS' }}</div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-title">Contract Status</div>
                            <div class="info-value">
                                <span class="badge bg-success">
                                    {{ $contract->contract_status }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-title">Termination Reason</div>
                            <div class="info-value text-muted">
                                @if ($contract->termination_reason)
                                    {{ $contract->termination_reason }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Registered By -->
            <div class="card info-card mt-4">
                <div class="card-body">

                    <div class="section-title">
                        <i class="bi bi-person-check"></i>
                        Registered By
                    </div>

                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="220">
                                <strong>Registered By</strong>
                            </td>
                            <td>{{ $contract->createdBy->name }}</td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Email</strong>
                            </td>
                            <td>{{ $contract->createdBy->email }}</td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Registered Date</strong>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($contract->created_at)->format('d M Y') }}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Signed Document</strong>
                            </td>
                            <td>
                                @if ($contract->contractFiles->count() > 0)
                                    @foreach ($contract->contractFiles as $file)
                                        <a href="{{ route($downloadRoute, $file->id) }}" target="_blank"
                                            class="mb-2 d-block">
                                            <i class="bi bi-download"></i>
                                            Download Contract {{ $loop->iteration }}
                                        </a>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

        </div>

    </div>
</div>
