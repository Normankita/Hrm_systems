<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Welcome, {{ Auth::user()->first_name }} 👋</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Employee Status -->
        <div class="bg-white shadow rounded-2xl p-4">
            <h2 class="text-gray-600 text-sm mb-1">Status</h2>
            <p class="text-xl font-semibold text-green-600">Active <!-- Replace with dynamic status --></p>
        </div>
    
        <!-- Net Salary -->
        <div class="bg-white shadow rounded-2xl p-4">
            <h2 class="text-gray-600 text-sm mb-1">Net Salary (This Month)</h2>
            <p class="text-xl font-semibold text-blue-600">TZS 1,200,000 <!-- Replace with dynamic salary --></p>
        </div>
    
        <!-- Today's Attendance -->
        <div class="bg-white shadow rounded-2xl p-4">
            <h2 class="text-gray-600 text-sm mb-1">Today’s Attendance</h2>
            <p class="text-md text-gray-800">Clocked in at 08:30 AM <!-- Replace with dynamic attendance time --></p>
        </div>
    
        <!-- Leave Balance -->
        <div class="bg-white shadow rounded-2xl p-4">
            <h2 class="text-gray-600 text-sm mb-1">Leave Balance</h2>
            <p class="text-md text-gray-800">Annual: 10 days <!-- Replace with dynamic annual leave --></p>
            <p class="text-md text-gray-800">Sick: 5 days <!-- Replace with dynamic sick leave --></p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Payslips -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Payslips</h3>
            <ul>
                <li class="border-b py-2 flex justify-between">
                    <span>March 2025</span>
                    <a href="#" class="text-blue-600 text-sm">Download</a> <!-- Replace with payslip route -->
                </li>
                <li class="border-b py-2 flex justify-between">
                    <span>February 2025</span>
                    <a href="#" class="text-blue-600 text-sm">Download</a>
                </li>
                <li class="border-b py-2 flex justify-between">
                    <span>January 2025</span>
                    <a href="#" class="text-blue-600 text-sm">Download</a>
                </li>
                <!-- Loop through dynamic payslips -->
            </ul>
        </div>
    
        <!-- Recent Leave Requests -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Leave Requests</h3>
            <ul>
                <li class="border-b py-2">
                    <span class="text-sm">Annual Leave: 2025-03-10 to 2025-03-15</span>
                    <span class="ml-2 text-xs text-green-500">(Approved)</span>
                </li>
                <li class="border-b py-2">
                    <span class="text-sm">Sick Leave: 2025-02-20 to 2025-02-22</span>
                    <span class="ml-2 text-xs text-yellow-500">(Pending)</span>
                </li>
                <!-- Loop through dynamic leave requests -->
            </ul>
        </div>
    </div>
    
</div>
