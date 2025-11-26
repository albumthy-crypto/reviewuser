<?php   include '../components/header.php' ?>
<?php   include '../components/Model.php' ?>


<body>

    <div id='container'>
        <section class="cards-branches" aria-label="Statistics">

            <div class="branches">
                <div class="table-header">
                    <h4 id="tableTitle" class="mb-3 fw-bold text-secondary">Total Users Branches</h4>

                    <div class="d-flex align-items-center gap-2">
                        <!-- Filter Button -->
                        <div class="dropdown">
                            <form class="btn btn-outline-white dropdown-toggle-t" type="button" id="uploadForm"
                                action="/RUT24/backend/import_employee.php" method="POST" enctype="multipart/form-data">
                                <input type="file" name="employee_file" id="employee_file" accept=".csv, .xlsx"
                                    style="display: none;" />
                                <button type="button" class="btn btn-outline-white dropdown-toggle-t"
                                    onclick="document.getElementById('employee_file').click();">
                                    <i class="fas fa-upload"></i>
                                </button>

                            </form>

                            <button class="btn btn-outline-white dropdown-toggle-t" type="button" id="Switch"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-file-alt"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="Switch">
                                <li><a class="dropdown-item" href="#" data-target="#myModalAdd"
                                        data-source="../backend/generate_br.php"> Generate
                                        Branches</a></li>
                                <li><a class="dropdown-item" href="#"
                                        data-source="../backend/generate_pdf_ho.php">Generate
                                        Head
                                        Office</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#" data-source="../backend/get_usersALL.php">All
                                        Users</a></li>
                            </ul>
                            <button class="btn btn-outline-white dropdown-toggle-t" type="button" id="Switch"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-layer-group"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="Switch">
                                <li><a class="dropdown-item" href="#" data-source="../backend/get_usersBR.php">User
                                        Branches</a></li>
                                <li><a class="dropdown-item" href="#" data-source="../backend/get_usersHO.php">User Head
                                        Office</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#" data-source="../backend/get_usersALL.php">All
                                        Users</a></li>
                            </ul>
                            <button class="btn btn-outline-white dropdown-toggle" type="button" id="filterMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterMenuButton">

                                <li class="dropdown-item **dropend**">

                                    <a class="dropdown-item dropdown-toggle" href="#"
                                        data_source="../backend/filter_period.php" role="button">
                                        Period ID
                                    </a>

                                    <ul class="dropdown-menu" id="periodFilter">
                                        <li class="dropdown-item" value="">All Periods</li>
                                        <li class="dropdown-item" value="1">Period 1</li>
                                        <li class="dropdown-item" value="2">Period 2</li>
                                        <li class="dropdown-item" value="3">Period 3</li>
                                    </ul>

                                </li>
                                <li><a class="dropdown-item" href="#">Active Users</a></li>
                                <li><a class="dropdown-item" href="#">Inactive Users</a></li>
                                <li><a class="dropdown-item" href="#">Expired</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">All Users</a></li>
                            </ul>

                        </div>

                    </div>

                    <div class="table-wrapper">
                        <table id="userTable" class="display nowrap" style="width:100%">
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
                        </table>
                    </div>

                </div>
            </div>
        </section>

        <section class="cards-users" aria-label="Statistics">
            <div class="card-body">
                <table id="example" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>USER NAME</th>
                            <th>ROLE</th>
                            <th>CREATION</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </section>

    </div>

    <!-- The Modal Choose date for Generating Report -->
    <div class="modal" id="myModalAdd">
        <div class="modal-dialog ">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Review Period</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body ">
                    <form method="post" action="../backend/generate_br.php">
                        <div class="form-group">
                            <label>
                                <i>Please choosing Review Period ID</i>
                            </label>
                            <select class="form-control" name="review_period_id"> <?php
																	$periods = json_decode(getDataAssoc('tbl_review_period',''), true);
																	
																	if (empty($periods)) {
																		echo "
																				<option disabled selected>Review Period Doesn't Exist</option>";
																	} else {
																		foreach ($periods as $key => $period) {
																			echo "
																				<option value='" . $period['Periodid'] . "'>". $period['Reviewperiod']." - " .$period['Reviewdate']."</option>";
																		}
																	}
																?> </select>
                        </div>
                        <input type="hidden" name="created_by" value="
																			<?=$_SESSION['user_name']?>" class="form-control">
                        <button type="submit" name="submit" class="form-control gradient-button " data-toggle="modal"
                            data-target="#loader">Submit </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

<script>
let holidays = []; // Global holidays array
let currentDate = new Date(2025, 9, 1); // Default to October 2025 (month 0-based)

// Reference to elements in your HTML
const monthYear = document.querySelector(".month-year");
const calendarGrid = document.getElementById("calendarGrid");
const holidayList = document.getElementById("holidayList");

// Load holidays dynamically from the database
async function loadHolidays() {
    try {
        // Fetch the holidays from PHP script
        const res = await fetch("backend/get_holidays.php");

        // Ensure we received a successful response
        if (!res.ok) {
            throw new Error("Failed to fetch holidays");
        }

        // Parse the JSON response
        holidays = await res.json();

        // Once holidays are loaded, render the calendar
        renderCalendar(currentDate);
    } catch (error) {
        console.error("Error loading holidays:", error);
    }
}

// Render calendar
function renderCalendar(date) {
    // Clear the calendar grid before rendering new dates
    calendarGrid.querySelectorAll(".date").forEach(d => d.remove());

    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    // Update the month-year header
    monthYear.textContent = date.toLocaleDateString('en-US', {
        month: 'long',
        year: 'numeric'
    });

    // Add blank spaces for days before the 1st day of the month
    for (let i = 0; i < firstDay; i++) {
        const blank = document.createElement("div");
        calendarGrid.appendChild(blank);
    }

    // Add the days of the month
    for (let day = 1; day <= lastDate; day++) {
        const cell = document.createElement("div");
        cell.classList.add("date");

        const thisDate = new Date(year, month, day);
        const dateString = thisDate.toISOString().split('T')[0];

        // Highlight holiday dates
        const holiday = holidays.find(h => h.date === dateString);
        if (holiday) cell.classList.add("holiday");
        if (new Date().toDateString() === thisDate.toDateString()) cell.classList.add("today");

        cell.textContent = day;
        calendarGrid.appendChild(cell);
    }

    // Render holiday list below calendar
    renderHolidayList(year, month);
}

// Show the list of holidays below the calendar
function renderHolidayList(year, month) {
    holidayList.innerHTML = "";
    const monthHolidays = holidays.filter(h => new Date(h.date).getMonth() === month);
    if (monthHolidays.length === 0) {
        holidayList.innerHTML = "<em>No holidays this month.</em>";
        return;
    }

    monthHolidays.forEach(h => {
        const item = document.createElement("div");
        item.classList.add("holiday-item");
        const d = new Date(h.date);
        item.innerHTML =
            `<strong>${d.getDate()} ${d.toLocaleString('default', {month: 'short'})}</strong> – ${h.name}` +
            (h.description && h.description !== "No description" ?
                `<p>${h.description}</p>` :
                "<p </p>");
        holidayList.appendChild(item);
    });
}

// Navigation buttons for the calendar
document.getElementById("prevMonth").onclick = () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
};

document.getElementById("nextMonth").onclick = () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
};

// Load holidays and render the calendar
loadHolidays();
</script>


<script>
$(document).ready(function() {
    $('#example').DataTable({
        ajax: '../backend/get_users.php',
        columns: [{
                data: 'id'
            },
            {
                data: 'username'
            },
            {
                data: 'role'
            },
            {
                data: 'created_at'
            } // Make sure this column exists in your DB
        ],
        responsive: true,
        scrollX: true
    });
});
</script>


<!-- script for Upload New Data -->

<script>
document.getElementById('employee_file').addEventListener('change', function() {
    document.getElementById('uploadForm').submit();
});
</script>

<!-- script for show/switching Data Tables -->
<script>
$(document).ready(function() {
    let table = $('#userTable').DataTable({
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
        filterLink.text(periodText);

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
    $('#userTable').DataTable({
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