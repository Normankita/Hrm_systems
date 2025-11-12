@extends('layouts.system')

@section('content')
    <div class="row justify content-center" id="emps">
        <div class="col-12">
            <!-- Card for displaying three catregories of allawances
                                                                                which are group, individual, category allowances -->
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <x-system.modal-button text="Disburse Allowance"
                                    id="disburse-allowance"></x-system.modal-button>
                                <x-system.modal title="Disburse Based On" id="disburse-allowance">
                                    <form action="{{ route('employee.manage.disbursements.create') }}" method="GET">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="basedOn">Disburse Based On</label>
                                                <select class="form-control" name="basedOn" id="basedOn" required>
                                                    <option value="all">All</option>
                                                    <option value="group"
                                                        {{ session('category') == 'group' ? 'selected' : '' }}>
                                                        Group</option>
                                                    <option value="individual"
                                                        {{ session('category') == 'individual' ? 'selected' : '' }}>
                                                        Individual</option>
                                                    <option value="category"
                                                        {{ session('category') == 'category' ? 'selected' : '' }}>
                                                        Category</option>
                                                </select>
                                                @error('basedOn')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    start
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </x-system.modal>
                            </div>
                            <h3 class="mb-2">Allowance Disbursement Directory</h3>
                        </div>

                        <!-- at right side, add search button with search mdi icon, a toggle buttons and a submit button -->
                        <div class="col-md-8">
                            <!-- aa search button with input search field with date picker -->
                            <select class="form-control" name="basedOn" id="basedOn"
                                v-on:change="selectionChange($event)" required>
                                <option value="all"
                                    {{ session('category') == 'all' || session('category') == null ? 'selected' : '' }}>All
                                </option>
                                <option value="group" {{ session('category') == 'group' ? 'selected' : '' }}>Group</option>
                                <option value="individual" {{ session('category') == 'individual' ? 'selected' : '' }}>
                                    Individual</option>
                                <option value="category" {{ session('category') == 'category' ? 'selected' : '' }}>Category
                                </option>
                            </select>
                        </div>

                        <div class="col-12 mt-5" id="individual">
                            <h4 style="text-transform: capitalize;" class="mt-3">
                                @{{ category }} Allowances
                            </h4>
                            <table
                                class="table table-bordered table-hover align-middle
                                    text-nowrap">
                                <thead class="table-light text-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Total Disbursememnts</th>
                                        <th>Based On</th>
                                        <th>Total Amount</th>
                                        <th>Reference No:</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(allowance, index) in paginated.data" :key="allowance.entrence_reference">
                                        <td>@{{ ++index }}</td>
                                        <td>@{{ allowance.total_disbursements }}</td>
                                        <td>@{{ allowance.type }}</td>
                                        <td>@{{ allowance.total }}</td>
                                        <td>@{{ allowance.entrence_reference }}</td>
                                        <td>
                                            <a class=""
                                                :href="viewRoute + '?ref=' + allowance.entrence_reference + '&basedOn=' + allowance.type"
                                                role="button">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="mt-6 flex items-center justify-between">
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">@{{ paginated.from }}</span>
                                    to <span class="font-medium">@{{ paginated.to }}</span>
                                    of <span class="font-medium">@{{ paginated.total }}</span> results
                                </p>

                                <nav class="flex space-x-1" aria-label="Pagination">
                                    <!-- Previous -->
                                    <button :disabled="!paginated.prev_page_url"
                                        @click="goToPage(paginated.current_page - 1)"
                                        class="px-3 py-2 rounded-md text-sm font-medium"
                                        :class="paginated.prev_page_url ? 'bg-white hover:bg-gray-100 text-gray-700' :
                                            'bg-gray-100 text-gray-400 cursor-not-allowed'">
                                        Previous
                                    </button>

                                    <!-- Page Links -->
                                    <button v-for="link in paginated.links" :key="link.label"
                                        @click="link.url && goToPage(extractPage(link.url))"
                                        class="px-3 py-2 rounded-md text-sm font-medium"
                                        :class="{
                                            'bg-indigo-600 text-white': link.active,
                                            'bg-white hover:bg-gray-100 text-gray-700': !link.active && link.url,
                                            'text-gray-400 cursor-not-allowed': !link.url
                                        }"
                                        v-html="link.label"></button>

                                    <!-- Next -->
                                    <button :disabled="!paginated.next_page_url"
                                        @click="goToPage(paginated.current_page + 1)"
                                        class="px-3 py-2 rounded-md text-sm font-medium"
                                        :class="paginated.next_page_url ? 'bg-white hover:bg-gray-100 text-gray-700' :
                                            'bg-gray-100 text-gray-400 cursor-not-allowed'">
                                        Next
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Fetching category string from session
        const category = "{{ session('category') }}";
        const app = Vue.createApp({
            data() {
                return {
                    categoryOpted: null,
                    category: 'all',
                    viewRoute: '{{ route('employee.manage.disbursements.group.view') }}',
                    optionsEmployees: [],
                    selectedEmployees: [],

                    paginated: {
                        data: [],
                        current_page: 1,
                        from: null,
                        to: null,
                        total: 0,
                        per_page: 20,
                        links: [],
                        prev_page_url: null,
                        next_page_url: null,
                    },
                    loading: false,
                    error: null,
                }
            },

            // ---------- Filters ----------
            filters: {
                currency(value, locales = 'en-US', currency = 'TZ') {
                    if (value === null || value === undefined) return '-';
                    return new Intl.NumberFormat(locales, {
                        style: 'currency',
                        currency,
                    }).format(value);
                },
            },

            // ---------- Lifecycle ----------
            created() {
                this.fetchPage();
            },

            mounted() {
                this.fetchDisbursements();
            },

            methods: {
                selectionChange(event) {
                    this.category = event.target.value;
                    this.fetchDisbursements();
                },
                async fetchDisbursements() {
                    await this.fetchPage();
                    console.log(this.paginated.data);
                },
                async fetchPage(page = 1) {
                    this.loading = true;
                    this.error = null;
                    const uri = "{{ route('disbursements.fetch') }}?category=" + this.category;
                    try {
                        const {
                            data
                        } = await axios.get(uri, {
                            params: {
                                page
                            },
                        });
                        this.paginated = data.disbursements;
                    } catch (e) {
                        this.error = e.response?.data?.message || 'Failed to load data';
                    } finally {
                        this.loading = false;
                    }
                },

                goToPage(page) {
                    if (page < 1 || page > this.paginated.last_page) return;
                    this.fetchPage(page);
                },

                // Extract page number from Laravel link URL: ?page=3
                extractPage(url) {
                    if (!url) return null;
                    const match = url.match(/[?&]page=(\d+)/);
                    return match ? parseInt(match[1], 10) : null;
                },
            },
        }).mount('#emps');
    </script>
@endsection
