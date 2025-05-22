@extends('layouts.system')

@section('_links')
    <!-- Bootstrap Icons CSS for checkmark -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f5f7fa;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .permission-group {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .permission-group h5 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .permission-box {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            margin: 5px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
            position: relative;
        }

        .permission-box input {
            display: none;
        }

        .permission-box.selected {
            background-color: #85299c;
            color: white;
            border-color: #007bff;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }

        .permission-box.deactive {
            background-color: #85299c;
            color: white;
            border-color: #007bff;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }

        .permission-box:hover {
            border-color: #007bff;
            background-color: #e9ecef;
        }

        .permission-box.selected:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .permission-box .check-icon {
            display: none;
            position: absolute;
            top: -8px;
            right: -8px;
            width: 20px;
            height: 20px;
            background-color: #28a745;
            color: white;
            border-radius: 50%;
            font-size: 12px;
            line-height: 20px;
            text-align: center;
        }

        .permission-box.selected .check-icon {
            display: block;
        }

        .btn-save {
            background: linear-gradient(45deg, #007bff, #00d4ff);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card p-4">
            <div class="card-body">
                <div class="header-section text-center mb-5">
                    <div class="role-icon mb-3">
                        <i class="bi bi-shield-lock-fill" style="font-size: 3rem; color: #85299c;"></i>
                    </div>
                    <h2 class="card-title mb-2">User Permissions Management</h2>
                    <p class="text-muted mb-0">Configure access rights for: <span class="badge bg-primary"
                            style="background-color: #85299c !important; color: white;">{{ $employee->full_name }}</span>
                    </p>
                    <div class="role-description mt-3">
                        <p class="text-muted small">Select the permissions you want to grant to this user. Permissions are
                            grouped by their functionality for easier management.</p>
                    </div>
                </div>

                <form id="permissionsForm" action="{{ route('admin.permissions.user.update', $employee->user->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div id="permissionsContainer">
                        @php
                            // Group permissions by group_name
                            $grouped = [];
                            foreach ($permissions->where('division', 'individual') as $permission) {
                                $groupName = $permission->group_name ?? 'Other';
                                if (!isset($grouped[$groupName])) {
                                    $grouped[$groupName] = [];
                                }
                                $grouped[$groupName][] = $permission;
                            }
                        @endphp

                        <x-system.forms.permissions-list-form :grouped="$grouped" :employee="$employee" />
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">Save Permissions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Toggle selected class and checkbox state on click
            $('.permission-box').on('click', function(e) {
                e.preventDefault();
                const $this = $(this);
                const $input = $this.find('input');
                const isChecked = $input.prop('checked');

                $input.prop('checked', !isChecked);
                $this.toggleClass('selected', !isChecked);
            });

            // Handle group select all functionality
            $('.group-select-all').on('change', function() {
                const groupName = $(this).data('group');
                const isChecked = $(this).prop('checked');

                // Find all permission boxes in this group
                const $permissionGroup = $(this).closest('.permission-group');
                const $permissionBoxes = $permissionGroup.find('.permission-box');

                // Update all checkboxes and their visual state
                $permissionBoxes.each(function() {
                    const $box = $(this);
                    const $input = $box.find('input');
                    $input.prop('checked', isChecked);
                    $box.toggleClass('selected', isChecked);
                });
            });

            // Handle form submission with AJAX
            $('#permissionsForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST', // Laravel handles PUT via _method
                    data: form.serialize(),
                    success: function(response) {
                        alert('Permissions updated successfully!');
                        window.location.href =
                            "{{ route('admin.employees.edit.permissions', $employee->id) }}";
                    },
                    error: function(xhr) {
                        alert('Error updating permissions: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endsection
