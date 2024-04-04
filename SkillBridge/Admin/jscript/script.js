document.addEventListener("DOMContentLoaded", function() {
    var searchInput = document.getElementById('searchInput');
    
    // Add event listener for keypress (Enter) in the search input field
    searchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            console.log("Enter key pressed");
            filterList(); // Filter the list when Enter key is pressed
        }
    });
    
    // Function to filter the list based on the search query
    function filterList() {
        console.log("Filtering list...");
        var filter = searchInput.value.trim().toLowerCase(); // Get the trimmed and lowercase search query
        var items = document.querySelectorAll('#data-list tr');

        // Loop through all list items and hide those that don't match the search query
        items.forEach(function(item) {
            var name = item.querySelector('td:nth-child(2)').textContent.toLowerCase();
            if (name.includes(filter)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }
});
var ascending = true; // Variable to track sorting order
        var currentColumn = 1; // Variable to track the currently sorted column

        // Function to sort the table by the specified column
        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("data-list");
            switching = true;

            while (switching) {
                switching = false;
                rows = table.rows;

                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("td")[columnIndex];

                    var xValue = x.innerHTML.toLowerCase();
                    var yValue = y.innerHTML.toLowerCase();

                    // Check if sorting order is ascending or descending
                    if (ascending) {
                        if (xValue > yValue) {
                            shouldSwitch = true;
                            break;
                        }
                    } else {
                        if (xValue < yValue) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }

            // Toggle sorting order
            ascending = !ascending;
            currentColumn = columnIndex;
        }

        // Function to filter the table by user type (employer or applicant)
        function filterUsers(userType) {
            var table, rows, i;
            table = document.getElementById("data-list");
            rows = table.rows;

            for (i = 1; i < rows.length; i++) {
                var typeCell = rows[i].getElementsByTagName("td")[2].innerHTML.toLowerCase();
                if (typeCell === userType.toLowerCase() || userType.toLowerCase() === "all") {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }