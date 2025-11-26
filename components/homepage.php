<?php
session_start();

  // Redirect if not logged in
  if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
      header("Location: ../RUT24X/login.php");
      exit();
  }

  $username = $_SESSION['username'];
  $role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <div class="app">
        <!-- SIDEBAR -->
        <aside class="sidebar" aria-label="Sidebar navigation">
            <div class="brand" role="banner">
                <img src="../assets/img/bnk.png" alt="Thy Logo" class="logo">

                <div class="brand-text">
                    <h1 class="label">USER Verification</h1>
                    <div class="small label">Admin Dashboard</div>
                </div>
            </div>

            <nav class="nav" aria-label="Main">
                <a href="#" class="nav-link active" data-target="overview">
                    <i class="fas fa-home icon"></i>
                    <span class="label">Overview</span>
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


            <div class="spacer"></div>

            <div class="small label">© <strong>RUT24</strong> 2025</div>
        </aside>

        <!-- TOPBAR -->
        <header class="topbar" role="banner">
            <div style="display: flex; align-items: center; gap: 12px">
                <button class="btn" id="sidebar-toggle" aria-label="Toggle sidebar">
                    ☰
                </button>
                <div class="search" aria-hidden="false">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="opacity: 0.8">
                        <path d="M21 21l-4.35-4.35" stroke="#374151" stroke-width="1.6" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <circle cx="11" cy="11" r="5" stroke="#374151" stroke-width="1.6" />
                    </svg>
                    <input type="search" placeholder="Search users, reports..." aria-label="Search" />
                </div>

            </div>

            <div class="user-toggle dropdown" id="userToggle" style="cursor: pointer;">
                <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Welcome, <?php echo htmlspecialchars($username); ?>
                    <img class="img-profile rounded-circle ms-2" src="../assets/img/profile.svg" alt="User Avatar"
                        width="32" height="32">
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-600"></i>
                            Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-600"></i>
                            Settings</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-list fa-sm fa-fw me-2 text-gray-600"></i>
                            Activity Log</a></li>
                    <li>
                        <a class="dropdown-item" href="#" id="theme-toggle">
                            <i class="fas fa-moon theme-icon fa-sm fa-fw mr-2"></i> Dark Mode
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-600"></i> Logout</a>
                    </li>
                </ul>
            </div>


        </header>

        <!-- MAIN CONTENT -->

        <main class="main" role="main">
            <section id="overview" class="content-section active">
                <?php include 'section_Overview.php'; ?>
            </section>
            <section id="users" class="content-section">
                <?php include 'section_Users.php'; ?>

            </section>
            <section id="reports" class="content-section"><?php include 'section_report.php' ?></section>
            <section id="settings" class="content-section">Settings content here</section>
            <section id="help" class="content-section">Help content here</section>
        </main>

    </div>
    <script src="../assets/js/homepage.js"></script>

    <script>
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.content-section');

    navLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();

            // Remove active class from all links and sections
            navLinks.forEach(l => l.classList.remove('active'));
            sections.forEach(s => s.classList.remove('active'));

            // Add active class to clicked link
            link.classList.add('active');

            // Show corresponding section
            const targetId = link.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active');
        });
    });
    </script>



</body>

</html>

<script>
const toggleTheme = () => {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme');
    html.setAttribute('data-theme', current === 'dark' ? 'light' : 'dark');
};
</script>