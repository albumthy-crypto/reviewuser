<?php   include '../components/header.php' ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>RUT24 Dashboard</title>

    <link rel="stylesheet" href="../assets/css/styleshomepage.css" />
    <!-- Chart.js CDN (v4+) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>

</head>

<body>
    <main class="main" role="main">
        <div class="container">
            <!-- Header row: Title -->
            <div style="
              display: flex;
              align-items: center;
              justify-content: space-between;
              gap: 12px;
            ">
                <div>
                    <h2 style="margin: 0">Overview</h2>
                    <div class="muted">Dashboard / Users Verification / T24</div>
                </div>
                <div class="small">
                    Updated: <strong id="last-updated">--</strong>
                </div>
            </div>

            <!-- Statistic Cards -->
            <section class="cards" aria-label="Statistics">
                <div class="card">
                    <div class="title">Head Office</div>
                    <div class="value" id="head-office-count">—</div>
                    <div class="meta">Total users in Head Office</div>
                </div>

                <div class="card">
                    <div class="title">Branches</div>
                    <div class="value" id="branches-count">—</div>
                    <div class="meta">Active branches</div>
                </div>

                <div class="card">
                    <div class="title">Division</div>
                    <div class="value" id="division-count">—</div>
                    <div class="meta">Registered divisions</div>
                </div>

                <div class="card">
                    <div class="title">Department</div>
                    <div class="value" id="department-count">—</div>
                    <div class="meta">Registered departments</div>
                </div>

                <div class="card">
                    <div class="title">Group Application</div>
                    <div class="value" id="group-app-count">—</div>
                    <div class="meta">Apps grouped by team</div>
                </div>

                <div class="card">
                    <div class="title">Users Login</div>
                    <div class="value" id="users-login-count">—</div>
                    <div class="meta">Users logged in today</div>
                </div>

                <div class="card">
                    <div class="title">Review File</div>
                    <div class="value" id="review-file-count">—</div>
                    <div class="meta">Pending review files</div>
                </div>

                <div class="card">
                    <div class="title">Review Period</div>
                    <div class="value" id="review-period-count">—</div>
                    <div class="meta">Current review cycle</div>
                </div>
            </section>

            <!-- Chart + Table -->
            <section class="panel-row">
                <div class="panel" aria-labelledby="chart-title">
                    <h2 id="chart-title">Active Users Over Time</h2>
                    <div style="height: 360px">
                        <canvas id="lineChart" aria-label="Line chart showing active users"></canvas>
                    </div>
                    <div class="small" style="margin-top: 10px">
                        Chart Users stand for Branches and Head Office.
                    </div>
                </div>

                <aside class="panel" aria-labelledby="recent-title">
                    <h2 id="recent-title">Recent Application Group (Head Office & Branches)</h2>

                    <table aria-describedby="recent-title">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Role</th>
                                <th>Last Login</th>
                            </tr>
                        </thead>
                        <tbody id="recent-users-tbody">
                            <!-- Rows injected by JS -->
                        </tbody>
                    </table>

                    <div style="
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                  margin-top: 12px;
                ">
                        <div class="small">Showing latest 8 entries</div>
                        <button class="btn" onclick="alert('Load more — implement backend')">
                            Load more
                        </button>
                    </div>
                </aside>
            </section>
        </div>
    </main>



</body>