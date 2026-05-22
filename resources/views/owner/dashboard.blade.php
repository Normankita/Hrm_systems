<div class="card card-default">
    <div class="px-6">
       
    </div>
</div>


<script>
    // Example for dynamic attendance chart using ApexCharts (replace with real data)
    var options = {
        chart: {
            type: 'bar',
            height: 300
        },
        series: [{
            name: 'Attendance',
            data: [140, 135, 132, 138, 142, 145, 148] // Dummy data for 7 days
        }],
        xaxis: {
            categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        }
    };
    new ApexCharts(document.querySelector("#attendanceChart"), options).render();
</script>
