<?php   include '../components/header.php' ?>

<body>
    <section>
        <button class="btn btn-outline-white dropdown-toggle-t" type="button" id="Switch" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fas fa-layer-group"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="Switch">
            <li><a class="dropdown-item" href="#" data-source="../backend/get_usersBR.php">Branches</a></li>
            <li><a class="dropdown-item" href="#" data-source="../backend/get_usersHO.php">Head
                    Office</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#" data-source="../backend/get_usersALL.php">All
                    Users</a></li>
        </ul>
        <div class="table-responsive-container">
            <table id="userTables" class="display nowrap data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>PERIOD ID</th>
                        <th>ID</th>
                        <th>STAFF ID</th>
                        <th>USER ID</th>
                        <th>NAME</th>
                        <th>APPLICATION TITLE</th>
                        <th>START DATE</th>
                        <th>END DATE</th>
                        <th>TIME</th>
                        <th>END TIME</th>
                        <th>EMAIL</th>
                        <th>COMPANY</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>
</body>


<script>
$(document).ready(function() {
    let table = $('#userTables').DataTable({
        ajax: '../backend/get_usersBR.php',
        columns: [{
                data: 'Periodid'
            }, {
                data: 'id'
            }, {
                data: 'staff_id'
            },
            {
                data: 'user_id'
            }, {
                data: 'user_name'
            }, {
                data: 'group_app'
            },
            {
                data: 'start_date'
            }, {
                data: 'end_date'
            },
            {
                data: 'start_time'
            }, {
                data: 'end_time'
            },
            {
                data: 'email'
            }, {
                data: 'co_code'
            }
        ],
        responsive: true,
        scrollX: true
    });

    $('.dropdown-item').on('click', function(e) {
        e.preventDefault();
        let newSource = $(this).data('source');
        let label = $(this).text().trim();

        // Update DataTable source
        table.ajax.url(newSource).load();

        // Update table title
        $('#tableTitle').text(`Total Users ${label}`);
    });
});
</script>

<script>
$(document).ready(function() {

    // 1. Target the list items within the period submenu
    $('.dropdown-item.dropend .dropdown-menu .dropdown-item').on('click', function(e) {
        // Prevent the default link action (if it were a link)
        e.preventDefault();

        // --- 2. Get the Period ID ---
        // Get the value attribute of the clicked list item (e.g., "", "1", "2")
        var periodId = $(this).attr('value');

        // Get the display text (e.g., "Period 1", "All Periods")
        var periodText = $(this).text().trim();

        // --- 3. Get the Target URL ---
        // Find the parent dropdown-toggle link to get the data_source URL
        var filterLink = $(this).closest('.dropend').find('.dropdown-toggle');
        var dataSourceUrl = filterLink.attr('data_source');

        console.log('Selected Period ID:', periodId);
        console.log('Selected Text:', periodText);
        console.log('Filter URL:', dataSourceUrl);

        // --- 4. EXECUTE THE FILTERING ACTION ---

        // Example: Update the main filter button text to show what's selected
        filterLink.text(periodText);

        // Example: You would typically make an AJAX request here to filter your data
        if (dataSourceUrl) {
            // Construct the data payload
            var postData = {
                period_id: periodId
            };


        } else {
            console.warn('No data_source URL found for filtering.');
        }
    });

});
</script>

<script>
$(document).ready(function() {
    $('#userTables').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        autoWidth: false,
        columnDefs: [{
            targets: [0, 1, 2, 3],
            className: 'dt-body-center'
        }]
    });
});
</script>