$(document).ready(function () {
    $("table").DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        paging: true,
        searching: true,
        ordering: true,
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
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
