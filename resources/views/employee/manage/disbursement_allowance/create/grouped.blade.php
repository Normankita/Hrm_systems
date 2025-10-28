<!-- Implemention a group disbursement allowance view -->
@extends('layouts.system')

@section('content')
    <div class="row justify-content-start" id="app">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-start mb-4">
                        <div class="col-sm-3 col-md-3">
                            <button class="btn btn-sm btn-primary" type="button" {{-- data-toggle="modal" data-target="#chooseAllowance" --}}
                                v-on:click="disburse">submit</button>
                        </div>
                    </div>
                    <table class="table table-sm dt-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" value="all" id="all-checker">
                                    #
                                </th>
                                <th>Group Name</th>
                                <th>Active Employees</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groups as $group)
                            @php
                                $group = (object) $group;
                            @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}
                                        <input type="checkbox" id="input-checker" value="{{ $group->id }}">
                                    </td>
                                    <td>{{ $group->name }}</td>
                                    <td>{{ $group->employeeCount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <div class="modal" tabindex="-1" role="dialog" id="chooseAllowance">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Disburse Selected</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row jsutify-content-start">
                                <div class="col-sm-12 col-md-6">
                                    <p class="lead">Available Allowances</p>
                                    <ul class="list-group">
                                        <li class="list-group-item" v-for="allowance in allowances" :key="allowance.id">
                                            <input type="checkbox" :value="allowance.id"
                                                v-on:change="toggleAllowanceSelection(allowance.id)">
                                            <b>@{{ allowance.name }}</b></i>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <p>Selected Allocated Allowance.</p>
                                    <ol class="list">
                                        <li v-for="group in SelectedGroups" :key="group.id">
                                            <p class="lead">
                                                @{{ group.name }}
                                            </p>
                                        </li>
                                    </ol>
                                    <small>
                                        Note that All Allowance Related to each Employee in the group will be disbursed.
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-primary"
                                v-on:click="confirmDisburse">disburse</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        const handler1 = new TableSelectionHandler('.dt-table', '#all-checker');

        const app = Vue.createApp({
            data() {
                return {
                    groups: [],
                    SelectedGroups: [],
                    allowances: @json($allowances),
                    selectedAllowances: []
                }
            },
            methods: {
                toggleAllowanceSelection(allowanceId) {
                    const index = this.selectedAllowances.indexOf(allowanceId);
                    if (index > -1) {
                        this.selectedAllowances.splice(index, 1);
                    } else {
                        this.selectedAllowances.push(allowanceId);
                    }
                },
                disburse() {
                    const groupsSelected = handler1.getSelected();
                    if (groupsSelected.length === 0) {
                        alert("No employees selected.");
                        return;
                    }
                    const myGroups = this.groups.filter(group => groupsSelected.includes(group.id.toString()));
                    this.SelectedGroups = myGroups;
                    $('#chooseAllowance').modal('show');
                },
                confirmDisburse() {
                    $('#chooseAllowance').modal('hide');

                    // Filter IDs of the selected groups
                    const groups = this.pluckIds(this.SelectedGroups, 'id');

                    // Sending an axios request to disburse the allowances
                    if (true) {
                        NProgress.start();
                        let requestGroupIds = this.pluckIds(this.SelectedGroups, 'id');
                        axios.post("{{ route('disburse.allowance.grouped') }}", {
                            groupIds: requestGroupIds,
                            allowanceIds: this.selectedAllowances
                        }).then(response => {
                            alert("Disbursement successful!");
                            location.reload();
                        }).catch(error => {
                            alert("An error occurred during disbursement.");
                        }).finally(() => {
                            NProgress.done();
                            $('#chooseAllowance').modal('hide');
                        });
                    }
                },
                pluckIds(array, key) {
                    return array.map(item => item[key]);
                }
            },
            computed: {
                // Add computed properties here if needed
            },
            mounted() {
                const loopGr = @json($groups);
                this.groups = loopGr.map(group => ({
                    ...group
                }));
            }
        }).mount('#app');
    </script>
@endsection
