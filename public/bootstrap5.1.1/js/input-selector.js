let selectedObject = [];
let domObjects = [];

$(document).ready(function () {

    // fetch the visible rows and listen for clicks
    domObjects = fetchVisibleRows();
    domObjects.forEach(input => {
        input.on('click', function () {
            inCheckerClick(input);
        });
    });

    // Now listen for typing in the search input
    $("div.dataTables_filter input").on("input", function () {
        // reset the selected object
        selectedObject = [];
        // reset the checked boxes
        resetAllSelected();
    });

    $(".all-checker").on("click", function () {
        selectedObject = [];
        let inputs = fetchVisibleRows();
        if (this.checked) {
            inputs.forEach((input) => {
                selectedObject.push(input.val());
                input.prop("checked", true);
            });
        } else {
            resetAllSelected();
        }
    });
});

function fetchVisibleRows() {
    var table = $(".dt-table").DataTable();
    // Get all visible rows (after search, filter, etc.)
    var visibleRows = table.rows({ search: "applied" }).nodes();
    let inputs = [];
    // Loop through each visible row
    $(visibleRows).each(function () {
        inputs.push($(this).find('input[type="checkbox"]'));
    });
    return inputs;
}

function inCheckerClick(element) {
    if (element.prop('checked')) {
        if (selectedObject.includes(element.value)) return;
        selectedObject.push(element.val());
    } else {
        if (!selectedObject.includes(element.value)) return;
        selectedObject.splice(selectedObject.indexOf(element.val()), 1);
    }
}

function resetAllSelected() {
    selectedObject = [];
    domObjects.forEach(input => {
        input.prop("checked", false);
    });
}
