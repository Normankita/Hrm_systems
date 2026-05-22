$(document).ready(function () {
    let table = $(".dt-table");
    for (let i = 0; i < table.length; i++) {
        let loopTable = $(table[i]);
        loopTable.DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            paging: true,
            searching: true,
            ordering: false,
            info: true,
        });
    }
});
