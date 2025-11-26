<?php
include 'config/configure.php';

session_start();
// Redirect if not logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$periods = getReviewPeriods($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUT24 Dashboard</title>
    <!-- Load Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/css/mix.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.2/css/select.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">

</head>

<body>
    <div id="app-wrapper" class="app">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button id="sidebar-toggle-mobile" class="sidebar-toggle-btn">
            <i class="fas fa-bars"></i>
        </button>
        <!-- SIDEBAR -->
        <aside id="sidebar" class="sidebar glass-sidebar">
            <!-- Brand Section -->
            <div class="brand" role="banner">
                <img src="../assets/img/bnk.png" alt="RUT24 Logo" class="logo">

                <div class="brand-text">
                    <h1 class="brand-title">RUT24</h1>
                    <div class="brand-subtitle">Verification</div>
                </div>
            </div>
            <div class="extend-dropdown-wrapper">
                <!-- Sidebar Toggle Button (Desktop) -->
                <button id="sidebar-toggle-desktop" class="sidebar-desktop-toggle-btn">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <!-- Navigation Links -->
            <nav class="navSide" aria-label="Main">
                <a href="#" class="nav-link active" data-target="overview">
                    <i class="fas fa-home icon"></i>
                    <span class="label">Dashboard</span>
                </a>
                <a href="#" class="nav-link" data-target="users">
                    <i class="fas fa-user icon"></i>
                    <span class="label">Users</span>
                </a>
                <a href="#" class="nav-link" data-target="reports">
                    <i class="fas fa-chart-bar icon"></i>
                    <span class="label">Reports</span>
                </a>
                <a href="#" class="nav-link" data-target="settings">
                    <i class="fas fa-cog icon"></i>
                    <span class="label">Settings</span>
                </a>
                <a href="#" class="nav-link" data-target="help">
                    <i class="fas fa-question-circle icon"></i>
                    <span class="label">Help</span>
                </a>

            </nav>

            <!-- Footer (Placed inside sidebar for fixed width) -->
            <footer class="footer">
                <div class="footer-text">© <strong class="footer-strong">RUT24</strong> 2025</div>

            </footer>
        </aside>

        <!-- MAIN CONTENT CONTAINER -->
        <div class="main-container">

            <!-- TOPBAR (Nav) -->
            <div class="topbar-nav">
                <!-- User Profile Dropdown -->
                <div class="user-dropdown-wrapper">
                    <button id="userDropdown" type="button" class="user-dropdown-btn" aria-expanded="false"
                        aria-haspopup="true">
                        <!-- Replaced PHP with static placeholder -->
                        <?php echo htmlspecialchars($username); ?>
                        <img class="img-profile rounded-circle ms-2" src="../assets/img/profile.svg" alt="User Avatar"
                            width="32" height="32">
                    </button>

                    <!-- Dropdown menu (using placeholder JS for interaction) -->
                    <div id="userToggleMenu" class="user-dropdown-menu hidden opacity-0 scale-95" role="menu"
                        aria-orientation="vertical" aria-labelledby="userDropdown">
                        <div class="menu-section" role="none">
                            <a href="#" class="dropdown-item rounded-t" role="menuitem">
                                <i class="fas fa-user fa-sm fa-fw icon-small-mr icon-indigo"></i> Profile
                            </a>
                            <a href="#" class="dropdown-item" role="menuitem">
                                <i class="fas fa-cogs fa-sm fa-fw icon-small-mr icon-indigo"></i> Settings
                            </a>
                            <a href="#" class="dropdown-item" role="menuitem">
                                <i class="fas fa-list fa-sm fa-fw icon-small-mr icon-indigo"></i> Activity Log
                            </a>
                        </div>
                        <div class="menu-section border-t" role="none">
                            <a href="#" id="theme-toggle" class="dropdown-item" role="menuitem">
                                <i class="fas fa-moon theme-icon fa-sm fa-fw icon-small-mr icon-gray"></i> Dark Mode
                            </a>
                        </div>
                        <div class="menu-section border-t" role="none">
                            <a href="#" class="dropdown-item text-red rounded-b" role="menuitem"
                                onclick="showLogoutModal()">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw icon-small-mr"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- MAIN CONTENT AREA -->
            <main class="main-content">
                <h2 class="main-title" id="main-header-title">Monitoring Dashboard</h2>
                <!-- 1. OVERVIEW VIEW (Default Dashboard Content) -->
                <div id="overview-view" class="view">
                    <!-- Dashboard Cards -->
                </div>

                <!-- 2. USERS VIEW (Blank Page as requested) -->

                <div id="users-view" class="view hidden">
                    <div class="table-footer sticky">

                        <div class="icon-group">
                            <!-- <a class="btn-icon" href="import_exployee"><i class="fas fa-cloud-arrow-down"></i></a> -->
                            <select id="pageSizeSelect" class="page-size-select">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <!-- Visible Group App filter in toolbar so users can filter without toggling the filter-row -->
                            <select id="groupAppToolbar" class="toolbar-select" aria-label="Group App filter">
                                <option value="">All Group Apps</option>
                            </select>
                            <!-- Data source selector (Branch / Head Office) -->
                            <select id="dataSourceSelect" class="toolbar-select" aria-label="Data source">
                                <option value="">Filter</option>
                                <option value="get_usersBR.php">Branch</option>
                                <option value="get_usersHO.php">Head Office</option>
                            </select>
                            <!-- Column selector + filter input for filtering any column or global -->
                            <select id="columnSelector" class="toolbar-select" aria-label="Select column to filter">
                                <option value="-1">All Columns</option>
                            </select>
                            <input id="columnFilterInput" class="toolbar-input" type="search" placeholder="Filter value"
                                aria-label="Filter value">
                            <button id="applyColumnFilterBtn" class="toolbar-btn">Apply</button>
                            <button id="clearColumnFilterBtn" class="toolbar-btn">Clear</button>

                            <!-- External Import button (kept outside DataTables buttons to avoid layout shifts) -->
                            <button id="importColumnFilterBtn" class="toolbar-btn">
                                <i class="fas fa-file-import"></i>
                            </button>

                            <!-- Container where DataTables buttons will be injected -->
                            <div id="dtButtonsContainer" class="dt-buttons-toolbar" aria-hidden="false"></div>

                            <!-- Hidden file input to accept CSV/XLS/XLSX for import -->
                            <input id="fileImportInput" type="file"
                                accept=".csv,.xls,.xlsx,text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                style="display:none" />

                            <!-- Import Drawer Overlay and Panel -->
                            <div id="importDrawerOverlay" class="overlay hidden"></div>
                            <div id="importDrawer" class="modal hidden" role="dialog" aria-label="Upload Data File">
                                <div class="modal">
                                    <div class="modal-content">

                                        <!-- Header -->
                                        <div class="modal-header">
                                            <p class="modal-title">Upload Data File</p>
                                            <button id="importDrawerClose" class="icon-btn"
                                                aria-label="Close import drawer">
                                                <span class="material-symbols-outlined">close</span>
                                            </button>
                                        </div>

                                        <!-- File Upload Progress (placeholder / will be updated dynamically) -->
                                        <div class="file-card">
                                            <div class="file-icon">
                                                <span class="material-symbols-outlined">description</span>
                                            </div>
                                            <div class="file-info">
                                                <div class="file-header">
                                                    <p class="file-name">No file selected</p>
                                                    <p class="file-progress">0%</p>
                                                </div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width:0%"></div>
                                                </div>
                                            </div>
                                            <div class="file-action">
                                                <button class="icon-btn" id="fileChooseBtn" title="Choose file">
                                                    <span class="material-symbols-outlined">attach_file</span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Hidden file input preserved for existing JS -->
                                        <input id="drawerFileInput" type="file" style="display:none"
                                            accept=".csv,.xls,.xlsx,text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />

                                        <!-- Review Period (static placeholder from request) -->
                                        <div class="section">

                                            <!-- Modal Manual Add Periood -->
                                            <div class="manual-period">
                                                <!-- <h4>Add Custom Review Period</h4> -->
                                                <form method="POST" action="add_period.php">
                                                    <label>Start Date</label>
                                                    <input type="date" name="start_date" required>

                                                    <label>End Date</label>
                                                    <input type="date" name="end_date" required>
                                                    </br><label>Period Name</label>
                                                    <input type="text" name="period_name" required
                                                        placeholder="e.g. Custom Review">
                                                    <button type="submit" class="btn primary">Save Period</button>
                                                </form>
                                            </div>
                                            <div class="selected-period">
                                                <div>
                                                    <p class="selected-label">Selected Period</p>
                                                    <div class="selected-info">
                                                        <p class="selected-title">Q4 2023 Review</p>
                                                        <p class="selected-date">Oct 1 - Dec 31, 2023</p>
                                                    </div>
                                                </div>
                                                <button class="icon-btn primary" id="selectedPeriodClear">
                                                    <span class="material-symbols-outlined">close</span>
                                                </button>
                                            </div>
                                            <!-- End Modal Manual Add Periood -->
                                            <!-- Search + List -->
                                            <div class="period-list">
                                                <div class="search-box">
                                                    <span class="material-symbols-outlined">search</span>
                                                    <input type="search" placeholder="Search periods..."
                                                        id="periodSearch" />
                                                </div>
                                                <ul id="periodList">
                                                    <?php if (!empty($periods)): ?>
                                                    <?php foreach ($periods as $p): ?>
                                                    <li class="period-item <?php echo $p['is_active'] ? 'active' : ''; ?>"
                                                        data-id="<?php echo $p['id']; ?>">
                                                        <div class="period-check">
                                                            <?php if ($p['is_active']): ?>
                                                            <span class="material-symbols-outlined">check_circle</span>
                                                            <?php endif; ?>
                                                            <span><?php echo htmlspecialchars($p['period_name']); ?></span>
                                                        </div>
                                                        <span class="period-date">
                                                            <?php echo date("M j, Y", strtotime($p['start_date'])); ?> -
                                                            <?php echo date("M j, Y", strtotime($p['end_date'])); ?>
                                                        </span>
                                                    </li>
                                                    <?php endforeach; ?>
                                                    <?php else: ?>
                                                    <li class="period-item">No periods found</li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                            <!-- Period List in Modal -->

                                        </div>

                                        <!-- Footer -->
                                        <div class="modal-footer">
                                            <button class="btn cancel" id="drawerCancelBtn">Cancel</button>
                                            <button class="btn primary" id="drawerUploadBtn">Upload</button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>

                    <div class="table-responsive">
                        <table id="example" class="manage-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Staff ID</th>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>Group App</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>E-mail</th>
                                    <th>Co Code</th>
                                </tr>

                                <!-- FILTER ROW (hidden by default) -->
                                <tr class="filter-row" style="display:none;">
                                    <th><input class="column-search" placeholder="Search ID"></th>
                                    <th><input class="column-search" placeholder="Search Staff ID"></th>
                                    <th><input class="column-search" placeholder="Search User ID"></th>
                                    <th><input class="column-search" placeholder="Search User Name"></th>

                                    <th>
                                        <select id="groupAppFilter" class="column-search">
                                            <option value="">All Group Apps</option>
                                        </select>
                                    </th>

                                    <th><input class="column-search" placeholder="Search Start Date"></th>
                                    <th><input class="column-search" placeholder="Search End Date"></th>
                                    <th><input class="column-search" placeholder="Search Start Time"></th>
                                    <th><input class="column-search" placeholder="Search End Time"></th>
                                    <th><input class="column-search" placeholder="Search E-mail"></th>
                                    <th><input class="column-search" placeholder="Search Co Code"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $raw = getDataAssocbranch('tbl_importtest', '');
                                    $data = is_string($raw) ? json_decode($raw, true) : $raw;

                                    if (!is_array($data) || empty($data)) {
                                        echo '<tr><td colspan="11">No data available</td></tr>';
                                    } else {
                                        $i = 1;
                                        foreach ($data as $value) {
                                            // Safely read fields (avoid undefined index notices)
                                            $staff_id   = isset($value['staff_id'])   ? htmlspecialchars($value['staff_id'])   : '';
                                            $user_id    = isset($value['user_id'])    ? htmlspecialchars($value['user_id'])    : '';
                                            $user_name  = isset($value['user_name'])  ? htmlspecialchars($value['user_name'])  : '';
                                            $group_app  = isset($value['group_app'])  ? htmlspecialchars($value['group_app'])  : '';
                                            $start_date = isset($value['start_date']) ? htmlspecialchars($value['start_date']) : '';
                                            $end_date   = isset($value['end_date'])   ? htmlspecialchars($value['end_date'])   : '';
                                            $start_time = isset($value['start_time']) ? htmlspecialchars($value['start_time']) : '';
                                            $end_time   = isset($value['end_time'])   ? htmlspecialchars($value['end_time'])   : '';
                                            $email      = isset($value['email'])      ? htmlspecialchars($value['email'])      : '';
                                            $co_code    = isset($value['co_code'])    ? htmlspecialchars($value['co_code'])    : '';
                                    ?>
                                <tr>
                                    <td data-label="ID"><?php echo $i++; ?></td>
                                    <td data-label="Staff ID"><?php echo $staff_id; ?></td>
                                    <td data-label="User ID"><?php echo $user_id; ?></td>
                                    <td data-label="User Name"><?php echo $user_name; ?></td>
                                    <td data-label="Group App"><?php echo $group_app; ?></td>
                                    <td data-label="Start Date"><?php echo $start_date; ?></td>
                                    <td data-label="End Date"><?php echo $end_date; ?></td>
                                    <td data-label="Start Time"><?php echo $start_time; ?></td>
                                    <td data-label="End Time"><?php echo $end_time; ?></td>
                                    <td data-label="E-mail"><?php echo $email; ?></td>
                                    <td data-label="Co Code"><?php echo $co_code; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Custom Modal for Alerts (Replaces alert()) -->
                <div id="actionModal" class="modal-overlay hidden">
                    <div class="modal-content">
                        <h3 id="modalTitle" class="modal-title">Action</h3>
                        <p id="modalMessage" class="modal-text"></p>
                        <div class="modal-actions">
                            <button onclick="hideActionModal()" class="btn-cancel">Close</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <!-- 3. GENERIC VIEW (For all other non-dashboard views) -->
    <div id="generic-view" class="view hidden">
        <div
            style="background-color: var(--color-white); padding: 2rem; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
            <h3 id="generic-view-title"
                style="font-size: 1.5rem; font-weight: 700; color: var(--color-text-dark); margin-bottom: 0.5rem;">
                Reports Placeholder</h3>
            <p style="color: var(--color-text-medium);">Content for this administrative section
                (Reports,
                Settings, or Help) will appear here.</p>
        </div>
    </div>

    </main>
    </div>
    </div>

    <!-- Custom Modal Structure -->
    <div id="logoutModal" class="modal-overlay hidden">
        <div class="modal-content">
            <h3 class="modal-title">Ready to Leave?</h3>
            <p class="modal-text">Select "Logout" below if you are ready to end your current session.</p>
            <div class="modal-actions">
                <button onclick="hideLogoutModal()" class="btn-cancel">Cancel</button>
                <button onclick="console.log('Logging out...')" class="btn-logout">Logout</button>
            </div>
        </div>
    </div>

</body>
<!-- DataTables & extensions JS (required for Buttons & Select) -->
<!-- jQuery (required by DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/select/1.6.2/js/dataTables.select.min.js"></script>
<!-- Parsers for client-side import (CSV / Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.2/papaparse.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {
    // Keep a reference to the DataTable instance so the page-size selector works
    var table = $('#example').DataTable({
        searching: false,
        lengthChange: false,
        pageLength: 19,
        lengthMenu: [19, 50, 100, 200, ],
        info: true,
        responsive: true,
        scrollX: false,
        autoWidth: false,
        order: [
            [3, 'asc']
        ], // Default sort by User Name (A - Z)
        dom: 'frtip', // remove automatic 'B' placement so we can inject buttons into our toolbar
        buttons: [{
            extend: 'collection',
            text: '<i class="fas fa-file-pdf"></i> Export',
            className: 'dt-btn-modern',
            autoClose: true, // closes dropdown after selecting
            buttons: [{
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                    className: 'dt-btn-modern'
                },
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    className: 'dt-btn-modern'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'dt-btn-modern'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'dt-btn-modern btn-outline'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'dt-btn-modern btn-outline'
                },
                {
                    extend: 'colvis',
                    text: '<i class="fas fa-eye"></i> Columns',
                    className: 'dt-btn-modern colvis'
                }
            ]
        }],

        select: {
            style: 'single'
        },
        // keep header filters working with order
        orderCellsTop: true
    });

    // expose table globally so other scripts can call adjust/recalc
    window.manageTable = table;

    // ensure widths are calculated after init
    table.columns.adjust();
    if (table.responsive) table.responsive.recalc();

    // Move DataTables Buttons into our external toolbar container to avoid layout shifts
    try {
        if (table.buttons && typeof table.buttons === 'function') {
            // appendTo accepts selector or element
            table.buttons().container().appendTo('#dtButtonsContainer');
        }
    } catch (err) {
        console.warn('Failed to move DataTables buttons to external container', err);
    }

    // Open the import drawer when import buttons clicked
    function openImportDrawer() {
        $('#importDrawer, #importDrawerOverlay').removeClass('hidden').attr('aria-hidden', 'false');
    }

    function closeImportDrawer() {
        $('#importDrawer, #importDrawerOverlay').addClass('hidden').attr('aria-hidden', 'true');
        $('#drawerFileInput').val('');
    }

    $('#importBtn, #importColumnFilterBtn').on('click', function(e) {
        e.preventDefault();
        openImportDrawer();
    });

    // Drawer close/cancel handlers
    $('#importDrawerClose, #drawerCancelBtn, #importDrawerOverlay').on('click', function(e) {
        e.preventDefault();
        closeImportDrawer();
    });

    // Upload from drawer: POST multipart to server endpoint
    $('#drawerUploadBtn').on('click', function(e) {
        e.preventDefault();
        var input = document.getElementById('drawerFileInput');
        if (!input || !input.files || !input.files[0]) {
            alert('Please choose a file to upload.');
            return;
        }
        var file = input.files[0];
        var form = new FormData();
        form.append('employee_file', file);

        // show loading state
        $(this).prop('disabled', true).text('Uploading...');

        $.ajax({
            url: 'import_employee.php',
            method: 'POST',
            data: form,
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function(resp) {
            if (resp && resp.success) {
                var inserted = resp.inserted || 0;
                var modalTitle = document.getElementById('modalTitle');
                var modalMessage = document.getElementById('modalMessage');
                if (modalTitle) modalTitle.textContent = 'Import Results';
                if (modalMessage) modalMessage.innerHTML = '<p>Inserted ' + inserted +
                    ' rows.</p>';
                showActionModal();
                closeImportDrawer();
                // reload page / table to show new rows
                setTimeout(function() {
                    location.reload();
                }, 900);
            } else {
                var msg = (resp && resp.message) ? resp.message : 'Import failed';
                var modalMessage = document.getElementById('modalMessage');
                if (modalMessage) modalMessage.textContent = msg;
                showActionModal();
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Upload failed:', textStatus, errorThrown, jqXHR);
            var modalMessage = document.getElementById('modalMessage');
            var details = 'Upload failed';
            if (jqXHR && jqXHR.status) details += ' (HTTP ' + jqXHR.status + ')';
            if (jqXHR && jqXHR.responseText) {
                // show a short excerpt of server response for debugging
                var txt = jqXHR.responseText.substring(0, 1000);
                details += ': ' + txt;
            } else if (errorThrown) {
                details += ': ' + errorThrown;
            }
            if (modalMessage) modalMessage.textContent = details;
            showActionModal();
        }).always(function() {
            $('#drawerUploadBtn').prop('disabled', false).text('Upload');
        });
    });

    // Wire up per-column filter controls (inputs and selects).
    // Compute the real column index from the TH containing the control so indexes stay aligned.
    $('#example thead .column-search').each(function() {
        var $control = $(this);
        var colIndex = $control.closest('th').index();

        $control.on('keyup change clear', function() {
            var val = $control.val() || '';

            // If this control is a select (Group App), perform exact-match using regex
            if ($control.is('select')) {
                // Use substring search for selects to be more forgiving (trimmed values)
                if (val) {
                    table.column(colIndex).search(val).draw();
                } else {
                    table.column(colIndex).search('').draw();
                }
            } else {
                // Text input: use standard DataTables text search (substring)
                if (table.column(colIndex).search() !== val) {
                    table.column(colIndex).search(val).draw();
                }
            }
        });
    });

    // Change DataTable page length based on select dropdown
    $('#pageSizeSelect').on('change', function() {
        var pageSize = parseInt($(this).val(), 10);
        table.page.len(pageSize).draw();
    });

    // Row click -> show details in modal (single click)
    $('#example tbody').on('click', 'tr', function() {
        var rowData = table.row(this).data();
        if (!rowData) return;

        var labels = ['ID', 'Staff ID', 'User ID', 'User Name', 'Group App', 'Start Date',
            'End Date',
            'Start Time', 'End Time', 'E-mail', 'Co Code'
        ];
        var html = '<div class="detail-list">';
        for (var i = 0; i < labels.length; i++) {
            var value = rowData[i] !== undefined ? rowData[i] : '';
            html += '<div><strong>' + labels[i] + ':</strong> ' + $('<div>').text(value).html() +
                '</div>';
        }
        html += '</div>';

        // Populate the action modal and show it via helper
        var modalTitle = document.getElementById('modalTitle');
        var modalMessage = document.getElementById('modalMessage');
        if (modalTitle) modalTitle.textContent = 'Row Details';
        if (modalMessage) modalMessage.innerHTML = html;
        showActionModal();
    });

    // Populate Group App select with unique, trimmed values from column 4
    var groupColIndex = 4;
    var groupSelect = $('#groupAppFilter');
    var seen = {};
    table.column(groupColIndex).data().each(function(d) {
        if (d === null || d === undefined) return;
        // If cell contains HTML, extract text; then trim
        var text = $('<div>').html(d).text().trim();
        if (text && !seen[text]) {
            seen[text] = true;
            groupSelect.append($('<option>').val(text).text(text));
        }
    });

    // Also populate the toolbar Group App select (visible) and wire it to filter directly
    var $toolbarSelect = $('#groupAppToolbar');
    if ($toolbarSelect.length) {
        // add the same options
        Object.keys(seen).sort().forEach(function(key) {
            $toolbarSelect.append($('<option>').val(key).text(key));
        });

        // When user changes the toolbar select, apply the same column filter and sync the hidden select
        $toolbarSelect.on('change', function() {
            var val = $(this).val() || '';
            // apply to table column
            table.column(groupColIndex).search(val).draw();
            // sync hidden select value (if present)
            if (groupSelect.length) groupSelect.val(val);
            // if filter row is hidden, show it briefly to indicate filters active
            var $filterRow = $('#example thead tr.filter-row');
            if ($filterRow.length && $filterRow.is(':hidden')) {
                $filterRow.show();
                $('#toggleFiltersBtn').attr('aria-pressed', 'true');
                setTimeout(function() {
                    $filterRow.hide();
                    $('#toggleFiltersBtn').attr('aria-pressed', 'false');
                }, 1200);
            }
        });
    }

    // Helper: load table data from a backend JSON endpoint (expects { data: [...] })
    function loadTableFrom(url) {
        if (!url) return;
        $.getJSON(url)
            .done(function(resp) {
                var rows = (resp && resp.data) ? resp.data : [];
                // Map backend objects into DataTables row arrays
                var mapped = rows.map(function(r, i) {
                    return [
                        i + 1,
                        r.staff_id || '',
                        r.user_id || '',
                        r.user_name || '',
                        r.group_app || '',
                        r.start_date || '',
                        r.end_date || '',
                        r.start_time || '',
                        r.end_time || '',
                        r.email || '',
                        r.co_code || ''
                    ];
                });

                table.clear();
                if (mapped.length) table.rows.add(mapped);
                table.draw();

                // Rebuild Group App selects from new data
                seen = {};
                groupSelect.empty().append($('<option>').val('').text('All Group Apps'));
                $toolbarSelect.empty().append($('<option>').val('').text('All Group Apps'));
                table.column(groupColIndex).data().each(function(d) {
                    if (d === null || d === undefined) return;
                    var text = $('<div>').html(d).text().trim();
                    if (text && !seen[text]) {
                        seen[text] = true;
                        groupSelect.append($('<option>').val(text).text(text));
                        $toolbarSelect.append($('<option>').val(text).text(text));
                    }
                });
            })
            .fail(function(jqxhr, status, err) {
                console.error('Failed to load data from', url, status, err);
            });
    }

    // Data source selector: switch between Branch/HO data
    $('#dataSourceSelect').on('change', function() {
        var src = $(this).val();
        if (!src) return; // keep current
        loadTableFrom(src);
    });

    // Populate column selector for filtering any column (or global search)
    var $columnSelector = $('#columnSelector');
    var $columnFilterInput = $('#columnFilterInput');
    var $applyBtn = $('#applyColumnFilterBtn');
    var $clearBtn = $('#clearColumnFilterBtn');

    if ($columnSelector.length) {
        // Build options from the first header row (the visible labels)
        $('#example thead tr').first().find('th').each(function(idx) {
            var label = $(this).text().trim();
            if (label) {
                $columnSelector.append($('<option>').val(idx).text(label));
            }
        });
    }

    function applyColumnFilter() {
        var selected = $columnSelector.val();
        var term = ($columnFilterInput.val() || '').trim();

        if (selected === null) return;

        if (selected === '-1') {
            // global search across all columns
            table.search(term).draw();
        } else {
            var colIdx = parseInt(selected, 10);
            if (!isNaN(colIdx)) {
                table.column(colIdx).search(term).draw();
                // sync the hidden filter-row control if it exists
                var $filterRow = $('#example thead tr.filter-row');
                if ($filterRow.length) {
                    var $control = $filterRow.find('th').eq(colIdx).find('.column-search');
                    if ($control.length) $control.val(term);
                }
            }
        }
    }

    $applyBtn.on('click', function(e) {
        e.preventDefault();
        applyColumnFilter();
    });

    $clearBtn.on('click', function(e) {
        e.preventDefault();
        // Clear both global and per-column searches
        table.search('');
        table.columns().search('');
        table.draw();
        $columnFilterInput.val('');
        // clear hidden filter-row inputs
        $('#example thead tr.filter-row').find('.column-search').val('');
        // reset column selector to All
        $columnSelector.val('-1');
    });

    // allow pressing Enter in input to apply
    $columnFilterInput.on('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyColumnFilter();
        }
    });

    // Toggle filter-row visibility when filter icon clicked
    $('#toggleFiltersBtn').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var $filterRow = $('#example thead tr.filter-row');

        if ($filterRow.is(':visible')) {
            $filterRow.hide();
            $btn.attr('aria-pressed', 'false');
        } else {
            $filterRow.show();
            $btn.attr('aria-pressed', 'true');
            setTimeout(function() {
                var $first = $filterRow.find('.column-search').first();
                if ($first.length) $first.focus();
            }, 50);
        }
    });

    // Import file handler (CSV / Excel)
    function isHeaderRow(row) {
        if (!row || !row.length) return false;
        var sample = row.slice(0, 6).join(' ').toLowerCase();
        var keywords = ['id', 'staff', 'staff id', 'user id', 'user name', 'group', 'group app',
            'start date',
            'end date', 'email', 'e-mail', 'co code'
        ];
        var matches = 0;
        keywords.forEach(function(k) {
            if (sample.indexOf(k) !== -1) matches++;
        });
        return matches >= 2; // heuristic: if at least two header keywords found
    }

    function processParsedRows(rows) {
        if (!rows || !rows.length) return 0;
        // remove empty rows
        var filtered = rows.filter(function(r) {
            if (!r) return false;
            if (Array.isArray(r)) return r.some(function(c) {
                return String(c).trim() !== '';
            });
            // if object, consider values
            return Object.values(r).some(function(c) {
                return String(c).trim() !== '';
            });
        });

        if (!filtered.length) return 0;

        // drop header row if detected
        if (isHeaderRow(filtered[0])) {
            filtered = filtered.slice(1);
        }

        var startIndex = table.rows().count();
        var toAdd = [];
        filtered.forEach(function(r, idx) {
            var arr = Array.isArray(r) ? r : Object.values(r);
            // build row matching table columns (staff_id, user_id, user_name, group_app, start_date, end_date, start_time, end_time, email, co_code)
            // we will assign ID server-side style: sequential after current rows
            var id = startIndex + toAdd.length + 1;
            var staff_id = (arr[0] !== undefined) ? String(arr[0]).trim() : '';
            var user_id = (arr[1] !== undefined) ? String(arr[1]).trim() : '';
            var user_name = (arr[2] !== undefined) ? String(arr[2]).trim() : '';
            var group_app = (arr[3] !== undefined) ? String(arr[3]).trim() : '';
            var start_date = (arr[4] !== undefined) ? String(arr[4]).trim() : '';
            var end_date = (arr[5] !== undefined) ? String(arr[5]).trim() : '';
            var start_time = (arr[6] !== undefined) ? String(arr[6]).trim() : '';
            var end_time = (arr[7] !== undefined) ? String(arr[7]).trim() : '';
            var email = (arr[8] !== undefined) ? String(arr[8]).trim() : '';
            var co_code = (arr[9] !== undefined) ? String(arr[9]).trim() : '';

            toAdd.push([id, staff_id, user_id, user_name, group_app, start_date, end_date,
                start_time,
                end_time, email, co_code
            ]);
        });

        if (toAdd.length) {
            table.rows.add(toAdd).draw();

            // rebuild group selects
            seen = {};
            groupSelect.empty().append($('<option>').val('').text('All Group Apps'));
            $toolbarSelect.empty().append($('<option>').val('').text('All Group Apps'));
            table.column(groupColIndex).data().each(function(d) {
                if (d === null || d === undefined) return;
                var text = $('<div>').html(d).text().trim();
                if (text && !seen[text]) {
                    seen[text] = true;
                    groupSelect.append($('<option>').val(text).text(text));
                    $toolbarSelect.append($('<option>').val(text).text(text));
                }
            });
        }

        return toAdd.length;
    }

    function handleImportFile(file) {
        if (!file) return;
        var name = file.name || '';
        var ext = (name.split('.').pop() || '').toLowerCase();

        if (ext === 'csv' || file.type === 'text/csv') {
            Papa.parse(file, {
                complete: function(results) {
                    var count = processParsedRows(results.data || []);
                    var modalTitle = document.getElementById('modalTitle');
                    var modalMessage = document.getElementById('modalMessage');
                    if (modalTitle) modalTitle.textContent = 'Import Results';
                    if (modalMessage) modalMessage.innerHTML = '<p>Imported ' + count +
                        ' rows from CSV.</p>';
                    showActionModal();
                },
                error: function(err) {
                    console.error('CSV parse error', err);
                    var modalMessage = document.getElementById('modalMessage');
                    if (modalMessage) modalMessage.textContent =
                        'Failed to parse CSV file.';
                    showActionModal();
                },
                skipEmptyLines: true
            });
        } else {
            // Try to parse as Excel
            var reader = new FileReader();
            reader.onload = function(e) {
                try {
                    var data = new Uint8Array(e.target.result);
                    var workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    var first = workbook.SheetNames[0];
                    var sheet = workbook.Sheets[first];
                    var rows = XLSX.utils.sheet_to_json(sheet, {
                        header: 1,
                        raw: false
                    });
                    var count = processParsedRows(rows || []);
                    var modalTitle = document.getElementById('modalTitle');
                    var modalMessage = document.getElementById('modalMessage');
                    if (modalTitle) modalTitle.textContent = 'Import Results';
                    if (modalMessage) modalMessage.innerHTML = '<p>Imported ' + count +
                        ' rows from Excel.</p>';
                    showActionModal();
                } catch (err) {
                    console.error('Excel parse error', err);
                    var modalMessage = document.getElementById('modalMessage');
                    if (modalMessage) modalMessage.textContent = 'Failed to parse Excel file.';
                    showActionModal();
                }
            };
            reader.onerror = function(err) {
                console.error('File read error', err);
                var modalMessage = document.getElementById('modalMessage');
                if (modalMessage) modalMessage.textContent = 'Failed to read file.';
                showActionModal();
            };
            reader.readAsArrayBuffer(file);
        }
    }

    // File input change -> parse and import
    $('#fileImportInput').on('change', function(e) {
        var f = this.files && this.files[0];
        if (!f) return;
        handleImportFile(f);
        // clear selection so same file can be re-chosen if needed
        $(this).val('');
    });
});
</script>

<script>
// --- Core Application Logic ---
const sidebar = document.getElementById('sidebar');
const appWrapper = document.getElementById('app-wrapper');
const toggleButtonMobile = document.getElementById('sidebar-toggle-mobile');
const toggleButtonDesktop = document.getElementById('sidebar-toggle-desktop');
const dropdownButton = document.getElementById('userDropdown');
const dropdownMenu = document.getElementById('userToggleMenu');
const logoutModal = document.getElementById('logoutModal');
const themeToggle = document.getElementById('theme-toggle');
const mainHeaderTitle = document.getElementById('main-header-title');

// View Containers
const viewContainers = {
    'overview': document.getElementById('overview-view'),
    'users': document.getElementById('users-view'),
    // Reports, Settings, and Help will use the generic view
    'generic': document.getElementById('generic-view')
};
const genericViewTitle = document.getElementById('generic-view-title');


// Helper to load the Overview content from server-side PHP and inject into the overview view
function loadOverviewSession() {
    var overviewEl = viewContainers.overview;
    if (!overviewEl) return;

    // show a loading indicator while fetching
    overviewEl.innerHTML = '<div class="loading" style="padding:1rem">Loading overview...</div>';

    fetch('overview-view.php', {
            credentials: 'same-origin'
        })
        .then(function(response) {
            if (!response.ok) throw new Error('Network response was not ok: ' + response.status);
            return response.text();
        })
        .then(function(html) {
            // insert server-rendered HTML into the overview container
            overviewEl.innerHTML = html;
        })
        .catch(function(err) {
            console.error('Failed to load...', err);
            overviewEl.innerHTML =
                '<div class="error" style="color:#c00; padding:1rem">Failed to load overview content...</div>';
        });
}

// Function to show the correct view
function switchView(target) {
    // 1. Hide all views
    Object.values(viewContainers).forEach(view => {
        view.classList.add('hidden');
    });

    // 2. Set the main header title
    // Capitalize the first letter for the display title
    mainHeaderTitle.textContent = target.charAt(0).toUpperCase() + target.slice(1);

    // 3. Show the correct view based on the target
    if (target === 'overview') {
        // show the overview container and load server-side content
        viewContainers.overview.classList.remove('hidden');
        loadOverviewSession();
    } else if (target === 'users') {
        viewContainers.users.classList.remove('hidden');
        // ensure table recalculates when users view becomes visible
        setTimeout(function() {
            if (window.manageTable) {
                window.manageTable.columns.adjust();
                if (window.manageTable.responsive) window.manageTable.responsive.recalc();
            }
        }, 80);
    } else {
        // Use the generic view for Reports, Settings, Help, etc.
        genericViewTitle.textContent = target.charAt(0).toUpperCase() + target.slice(1) + ' Placeholder';
        viewContainers.generic.classList.remove('hidden');
    }
}


// 1. Sidebar Toggle (Mobile Responsiveness)
toggleButtonMobile.addEventListener('click', () => {
    sidebar.classList.toggle('sidebar-mobile-hidden');
});

// 2. Sidebar Toggle (Desktop Collapse)
// (Handler moved into the jQuery ready block above to avoid referencing DOM elements before they exist)

// 3. User Dropdown Toggle
dropdownButton.addEventListener('click', (e) => {
    e.stopPropagation();
    const isVisible = dropdownMenu.classList.toggle('hidden');
    dropdownMenu.classList.toggle('opacity-0', !isVisible);
    dropdownMenu.classList.toggle('scale-95', !isVisible);
    dropdownMenu.classList.toggle('scale-100', isVisible);
});

document.addEventListener('click', (e) => {
    if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.add('hidden', 'opacity-0', 'scale-95');
        dropdownMenu.classList.remove('scale-100');
    }
});

// 4. Modal Functions
// Logout modal helpers
function showLogoutModal() {
    if (logoutModal) logoutModal.classList.remove('hidden');
}

function hideLogoutModal() {
    if (logoutModal) logoutModal.classList.add('hidden');
}

// Action (details) modal helpers
function showActionModal() {
    var actionModalEl = document.getElementById('actionModal');
    if (actionModalEl) actionModalEl.classList.remove('hidden');
}

function hideActionModal() {
    var actionModalEl = document.getElementById('actionModal');
    if (actionModalEl) actionModalEl.classList.add('hidden');
}

// 5. Dark Mode Toggle (Simplified Example)
themeToggle.addEventListener('click', (e) => {
    e.preventDefault();
    document.body.classList.toggle('dark-mode');
    // Update the icon and text to reflect the current state
    const icon = themeToggle.querySelector('.theme-icon');
    const isDark = document.body.classList.contains('dark-mode');
    icon.classList.toggle('fa-sun', isDark);
    icon.classList.toggle('fa-moon', !isDark);
    themeToggle.lastChild.textContent = isDark ? ' Light Mode' : ' Dark Mode';
});

// 6. Navigation State and Content Toggle
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();

        // 6a. Remove active class from all and add to clicked link
        document.querySelectorAll('.nav-link').forEach(l => {
            l.classList.remove('active');
        });
        link.classList.add('active');

        // 6b. Switch the content view
        const target = link.getAttribute('data-target');
        switchView(target);

        // 6c. Hide sidebar on mobile after click
        if (window.innerWidth < 1024) {
            sidebar.classList.add('sidebar-mobile-hidden');
        }
    });
});

// Initial setup to ensure the correct view is shown on load
window.addEventListener('load', () => {
    switchView('overview');
});
</script>

<!-- Javascritp for Modal Upload Data File -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Elements
    const modal = document.querySelector(".overlay");
    const closeBtn = document.getElementById("importDrawerClose");
    const fileInput = document.getElementById("drawerFileInput");
    const fileChooseBtn = document.getElementById("fileChooseBtn");
    const fileNameEl = document.querySelector(".file-name");
    const fileProgressEl = document.querySelector(".file-progress");
    const progressFill = document.querySelector(".progress-fill");
    const selectedPeriodClear = document.getElementById("selectedPeriodClear");
    const searchInput = document.querySelector(".search-box input");
    const periodItems = document.querySelectorAll(".period-item");
    const cancelBtn = document.getElementById("drawerCancelBtn");
    const uploadBtn = document.getElementById("drawerUploadBtn");

    // Close modal
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // File choose
    fileChooseBtn.addEventListener("click", () => {
        fileInput.click();
    });

    fileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (file) {
            fileNameEl.textContent = file.name;
            // Simulate progress (for demo)
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                fileProgressEl.textContent = progress + "%";
                progressFill.style.width = progress + "%";
                if (progress >= 100) clearInterval(interval);
            }, 300);
        }
    });

    // Clear selected period
    selectedPeriodClear.addEventListener("click", () => {
        document.querySelector(".selected-title").textContent = "None selected";
        document.querySelector(".selected-date").textContent = "";
    });

    // Search filter
    searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase();
        periodItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(query) ? "flex" : "none";
        });
    });

    // Select period
    periodItems.forEach(item => {
        item.addEventListener("click", () => {
            // Remove active state + check_circle from all items
            periodItems.forEach(i => {
                i.classList.remove("active");
                const checkIcon = i.querySelector(".material-symbols-outlined");
                if (checkIcon && checkIcon.textContent === "check_circle") {
                    checkIcon.remove();
                }
            });

            // Add active state to clicked item
            item.classList.add("active");

            // Insert check_circle icon at the start of the clicked item
            const firstSpan = item.querySelector("span");
            if (firstSpan) {
                const checkIcon = document.createElement("span");
                checkIcon.className = "material-symbols-outlined";
                checkIcon.textContent = "check_circle";
                checkIcon.style.color = "var(--color-primary)";
                item.insertBefore(checkIcon, firstSpan);
            }

            // Update Selected Period box
            const title = item.querySelector("span").textContent;
            const date = item.querySelector(".period-date")?.textContent || "";
            document.querySelector(".selected-title").textContent = title;
            document.querySelector(".selected-date").textContent = date;
        });
    });


    // Footer buttons
    cancelBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    uploadBtn.addEventListener("click", () => {
        alert("Uploading file and period selection...");
        // Here you’d integrate with backend upload logic
    });
});
</script>

<!-- End of Modal Upload Data File -->

</html>