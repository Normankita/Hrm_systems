$(document).ready(function () {
    $table = $(".dt-table");
    for (let i = 0; i < $table.length; i++) {
        let table = $($table[i]);
        table.DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            // "lengthChange": false ,
            order: [[0, "asc"]],
            columnDefs: [
                {
                    orderable: true,
                    targets: [0],
                },
            ],
        });
    }
});
