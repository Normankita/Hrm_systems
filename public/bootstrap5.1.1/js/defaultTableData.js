$(document).ready(function () {
    $(".dt-table").DataTable({
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
                targets: [0, 6],
            },
        ],
    });
});
