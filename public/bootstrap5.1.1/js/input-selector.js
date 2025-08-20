class TableSelectionHandler {
    constructor(tableSelector, allCheckerSelector) {
        this.tableSelector = tableSelector;
        this.allCheckerSelector = allCheckerSelector;
        this.selectedObject = [];
        this.domObjects = [];

        $(document).ready(() => {
            this.initialize();
        });
    }

    initialize() {
        this.domObjects = this.fetchVisibleRows();
        this.attachCheckboxListeners();

        $(`div.dataTables_filter input`).on("input", () => {
            this.selectedObject = [];
            this.resetAllSelected();
        });

        $(this.allCheckerSelector).on("click", (event) => {
            this.selectedObject = [];
            const inputs = this.fetchVisibleRows();
            if (event.target.checked) {
                inputs.forEach((input) => {
                    this.selectedObject.push(input.val());
                    input.prop("checked", true);
                });
            } else {
                this.resetAllSelected();
            }
        });
    }

    fetchVisibleRows() {
        const table = $(this.tableSelector).DataTable();
        const visibleRows = table.rows({ search: "applied" }).nodes();
        const inputs = [];

        $(visibleRows).each((_, row) => {
            inputs.push($(row).find('input[type="checkbox"]'));
        });

        return inputs;
    }

    attachCheckboxListeners() {
        this.domObjects.forEach((input) => {
            input.on("click", () => {
                this.handleCheckboxClick(input);
            });
        });
    }

    handleCheckboxClick(element) {
        if (element.prop("checked")) {
            if (!this.selectedObject.includes(element.val())) {
                this.selectedObject.push(element.val());
            }
        } else {
            const index = this.selectedObject.indexOf(element.val());
            if (index !== -1) {
                this.selectedObject.splice(index, 1);
            }
        }
    }

    resetAllSelected() {
        this.selectedObject = [];
        this.domObjects.forEach((input) => {
            input.prop("checked", false);
        });
    }

    getSelected() {
        let selectedItesm = this.selectedObject;
        // filter out empy, undefined, or null values
        selectedItesm = selectedItesm.filter(item => item !== "" && item !== undefined && item !== null);
        return selectedItesm;
    }
}
