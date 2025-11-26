<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/OverviewDashboard.css">
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
</head>

<body class="app-body">
    <div class="app-root">
        <div class="layout">
            <!-- SideNavBar -->
            <!-- <aside class="sidenav">
                <div class="sidenav-inner">
                    <div class="sidenav-top">
                        <div class="brand">
                            <div class="brand-icon">
                                <span class="material-symbols-outlined">insights</span>
                            </div>
                            <h1 class="brand-title">Dashboard</h1>
                        </div>

                        <nav class="nav-links">
                            <a class="nav-link active" href="#">
                                <span class="material-symbols-outlined">dashboard</span>
                                <p>Dashboard</p>
                            </a>
                            <a class="nav-link" href="#">
                                <span class="material-symbols-outlined">task_alt</span>
                                <p>Task Tracking</p>
                            </a>
                            <a class="nav-link" href="#">
                                <span class="material-symbols-outlined">bar_chart</span>
                                <p>Reports</p>
                            </a>
                            <a class="nav-link" href="#">
                                <span class="material-symbols-outlined">settings</span>
                                <p>Settings</p>
                            </a>
                        </nav>
                    </div>

                    <div class="sidenav-bottom">
                        <div class="user-info">
                            <div class="avatar"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/...");'>
                            </div>
                            <div class="user-text">
                                <h1 class="user-name">Admin User</h1>
                                <p class="user-email">admin@example.com</p>
                            </div>
                        </div>
                        <a class="nav-link" href="#">
                            <span class="material-symbols-outlined">logout</span>
                            <p>Log out</p>
                        </a>
                    </div>
                </div>
            </aside> -->
            <main class="main-content">
                <div class="page">
                    <!-- PageHeading -->
                    <div class="page-heading">
                        <p class="page-title">Task Monitoring Dashboard</p>
                        <p class="page-subtitle">Welcome back, here's a summary of your tasks.</p>
                    </div>
                    <!-- Stats -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <p class="stat-label">Total Tasks</p>
                            <p class="stat-value">1,234</p>
                        </div>
                        <div class="stat-card">
                            <p class="stat-label">Pending</p>
                            <p class="stat-value pending">150</p>
                        </div>
                        <div class="stat-card">
                            <p class="stat-label">In Progress</p>
                            <p class="stat-value in-progress">85</p>
                        </div>
                        <div class="stat-card">
                            <p class="stat-label">Completed</p>
                            <p class="stat-value completed">1000</p>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="charts-grid">
                    <!-- Tasks by Quarter -->
                    <div class="chart-card chart-quarter">
                        <div class="chart-header">
                            <p class="chart-title">Tasks by Quarter</p>
                            <p class="chart-growth">+2.1% this year</p>
                        </div>
                        <div class="bar-chart">
                            <div class="bar q1" style="height:40%;"></div>
                            <p class="bar-label">Q1</p>
                            <div class="bar q2" style="height:80%;"></div>
                            <p class="bar-label">Q2</p>
                            <div class="bar q3" style="height:90%;"></div>
                            <p class="bar-label">Q3</p>
                            <div class="bar q4" style="height:10%;"></div>
                            <p class="bar-label">Q4</p>
                        </div>
                    </div>

                    <!-- Task Status Distribution -->
                    <div class="chart-card chart-status">
                        <p class="chart-title">Task Status Distribution</p>
                        <div class="donut-wrap">
                            <div class="donut">
                                <svg viewBox="0 0 36 36">
                                    <circle class="donut-pending" cx="18" cy="18" r="15.9" fill="none" stroke-width="3">
                                    </circle>
                                    <circle class="donut-progress" cx="18" cy="18" r="15.9" fill="none" stroke-width="3"
                                        stroke-dasharray="70,30" stroke-dashoffset="-20"></circle>
                                    <circle class="donut-completed" cx="18" cy="18" r="15.9" fill="none"
                                        stroke-width="3" stroke-dasharray="25,75" stroke-dashoffset="-90"></circle>
                                    <circle class="donut-overdue" cx="18" cy="18" r="15.9" fill="none" stroke-width="3"
                                        stroke-dasharray="10,90" stroke-dashoffset="-115"></circle>
                                </svg>
                                <div class="donut-center">
                                    <span class="donut-value">1,234</span>
                                    <span class="donut-label">Total Tasks</span>
                                </div>
                            </div>
                        </div>
                        <div class="legend">
                            <div class="legend-item"><span class="legend-dot completed"></span>Completed</div>
                            <div class="legend-item"><span class="legend-dot progress"></span>In Progress</div>
                            <div class="legend-item"><span class="legend-dot pending"></span>Pending</div>
                            <div class="legend-item"><span class="legend-dot overdue"></span>Overdue</div>
                        </div>
                    </div>
                </div>
                <!-- Task Table -->
                <div class="task-table">
                    <h2 class="task-title">Task Details</h2>
                    <div class="table-wrap">
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th>Task ID</th>
                                    <th>Assigned to</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="task-id">#T5821</td>
                                    <td>John Doe</td>
                                    <td><span class="badge completed">Completed</span></td>
                                    <td>2023-10-25</td>
                                    <td class="text-right"><button class="link-btn">View</button></td>
                                </tr>
                                <tr>
                                    <td class="task-id">#T5822</td>
                                    <td>Jane Smith</td>
                                    <td><span class="badge progress">In Progress</span></td>
                                    <td>2023-11-15</td>
                                    <td class="text-right"><button class="link-btn">Complete</button></td>
                                </tr>
                                <tr>
                                    <td class="task-id">#T5823</td>
                                    <td>Mike Johnson</td>
                                    <td><span class="badge pending">Pending</span></td>
                                    <td>2023-12-01</td>
                                    <td class="text-right"><button class="link-btn">Start</button></td>
                                </tr>
                                <tr>
                                    <td class="task-id">#T5824</td>
                                    <td>Sarah Lee</td>
                                    <td><span class="badge overdue">Overdue</span></td>
                                    <td>2023-10-01</td>
                                    <td class="text-right"><button class="link-btn">View</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
            <!-- Right Filter Sidebar -->
            <aside class="filter-sidebar">
                <div class="filter-inner">
                    <h3 class="filter-title">Filters</h3>

                    <div class="filter-group">
                        <div class="filter-item">
                            <label for="status-filter">Status</label>
                            <select id="status-filter">
                                <option selected>All</option>
                                <option value="pending">Pending</option>
                                <option value="in-progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>

                        <div class="filter-item">
                            <label for="period-filter">Period</label>
                            <select id="period-filter">
                                <option selected>All Year</option>
                                <option value="q1">Q1</option>
                                <option value="q2">Q2</option>
                                <option value="q3">Q3</option>
                                <option value="q4">Q4</option>
                            </select>
                        </div>

                        <div class="filter-item">
                            <label for="assignee-filter">Assignee</label>
                            <input id="assignee-filter" type="text" placeholder="Search by name..." />
                        </div>
                    </div>

                    <div class="progress-section">
                        <h3 class="progress-title">Quarterly Progress</h3>
                        <div class="progress-list">
                            <div class="progress-item">
                                <div class="progress-labels">
                                    <span>Q1 Completion</span>
                                    <span>95%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill completed" style="width:95%"></div>
                                </div>
                            </div>

                            <div class="progress-item">
                                <div class="progress-labels">
                                    <span>Q2 Completion</span>
                                    <span>80%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill completed" style="width:80%"></div>
                                </div>
                            </div>

                            <div class="progress-item">
                                <div class="progress-labels">
                                    <span>Q3 Progress</span>
                                    <span>45%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill in-progress" style="width:45%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>


        </div>
    </div>


</body>


</html>