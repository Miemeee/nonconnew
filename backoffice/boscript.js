// Function to get unique values from each column and store them in a dictionary
function getUniqueValuesFromColumn() {
    var unique_col_values_dict = {};

    // Select all elements with the class 'table-filter'
    var allFilters = document.querySelectorAll(".table-filter");

    allFilters.forEach((filter_i) => {
        // Get the column index from the parent element's attribute
        var col_index = filter_i.parentElement.getAttribute("col-index");

        // Select all rows in the table body
        const rows = document.querySelectorAll("#emp-table > tbody > tr");

        rows.forEach((row) => {
            // Get the cell value for the current column index
            var cell_value = row.querySelector("td:nth-child("+col_index+")").innerHTML.trim();

            // Check if the column index already exists in the dictionary
            if (col_index in unique_col_values_dict) {
                // Add the cell value to the array if it's not already present
                if (!unique_col_values_dict[col_index].includes(cell_value)) {
                    unique_col_values_dict[col_index].push(cell_value);
                }
            } else {
                // Initialize the array with the first cell value
                unique_col_values_dict[col_index] = [cell_value];
            }
        });
    });

    // Update the select options with the unique values
    updateSelectOptions(unique_col_values_dict);
}

// Function to add <option> tags to the select elements based on unique values
function updateSelectOptions(unique_col_values_dict) {
    var allFilters = document.querySelectorAll(".table-filter");

    allFilters.forEach((filter_i) => {
        // Get the column index from the parent element's attribute
        var col_index = filter_i.parentElement.getAttribute('col-index');

        // Clear existing options (except the "all" option)
        filter_i.innerHTML = '<option value="all">All</option>';

        // Add new options based on unique values
        unique_col_values_dict[col_index].forEach((value) => {
            filter_i.innerHTML += `<option value="${value}">${value}</option>`;
        });
    });
}

// Function to filter rows based on selected filter values
function filter_rows() {
    var allFilters = document.querySelectorAll(".table-filter");
    var filter_value_dict = {};

    // Create a dictionary of selected filter values
    allFilters.forEach((filter_i) => {
        var col_index = filter_i.parentElement.getAttribute('col-index');
        var value = filter_i.value;
        if (value != "all") {
            filter_value_dict[col_index] = value;
        }
    });

    const rows = document.querySelectorAll("#emp-table tbody tr");

    rows.forEach((row) => {
        var display_row = true;

        // Check each row's cell values against the selected filter values
        allFilters.forEach((filter_i) => {
            var col_index = filter_i.parentElement.getAttribute('col-index');
            var row_cell_value = row.querySelector("td:nth-child(" + col_index + ")").innerHTML.trim();

            if (col_index in filter_value_dict && filter_value_dict[col_index] !== "all") {
                if (!row_cell_value.includes(filter_value_dict[col_index])) {
                    display_row = false;
                }
            }
        });

        // Show or hide the row based on the filter match
        row.style.display = display_row ? "table-row" : "none";
    });
}

// Initialize unique values and populate filter options on page load
window.onload = function() {
    getUniqueValuesFromColumn();
};