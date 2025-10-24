<!-- Implemention a group disbursement allowance view -->
@extends('layouts.system')

@section('content')
    <div class="row justify-content-start" id="app">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-start mb-4">
                        <div class="col-sm-3 col-md-3">
                            <button class="btn btn-sm btn-primary" type="button" v-on:click="disburse">submit</button>
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
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Disburse Selected</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Selected Groups.</p>
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-primary" v-on:click="confirmDisburse">disburse</button>
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
                }
            },
            methods: {
                disburse() {
                    const groupsSelected = handler1.getSelected();
                    if (groupsSelected.length === 0) {
                        alert("No employees selected.");
                        return;
                    }
                    var myGroups = this.groups.filter(group => groupsSelected.includes(group.id.toString()));
                    this.SelectedGroups = myGroups;
                    $('#chooseAllowance').modal('show');
                },
                confirmDisburse() {
                    // Logic to confirm disbursement
                    $('#chooseAllowance').modal('hide');
                    // sending an axios request to disburse the allowances
                    await response = axios.post("{{ route('disburse.allowance.grouped') }}", {
                        groups: this.SelectedGroups
                    }).then(response => {
                        // handle success
                        alert("Disbursement successful!");
                    }).catch(error => {
                        // handle error
                        alert("An error occurred during disbursement.");
                    });

                }
            },
            computed: {

            },
            mounted() {
                let loopGr = @json($groups);
                this.groups = loopGr.map(group => ({
                    ...group
                }));
            },
        }).mount('#app');
    </script>
@endsection
