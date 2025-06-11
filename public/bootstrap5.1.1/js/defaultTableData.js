$(document).ready(function () {
    $table = $(".dt-table");
    for (let i = 0; i < $table.length; i++) {
        let table = $($table[i]);
        console.log(table[i])
        table.DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            // "lengthChange": false ,
            order: [[1, "asc"]],
            columnDefs: [
                {
                    orderable: true,
                    targets: [0],
                },
            ],
        });
    }
});
