<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Load Inter font -->
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

    /* Minimal custom CSS for the glass effect */
    .glass-sidebar {
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    /* Set Inter as the primary font */
    body {
        font-family: 'Inter', sans-serif;
    }

    /* Scrollbar styling for better look */
    /* Default sidebar (expanded) */
    .sidebar {
        width: 240px;
        /* Full width of the sidebar when expanded */
        transition: width 0.3s ease;
    }

    /* Sidebar when collapsed */
    .sidebar.collapsed {
        width: 80px;
        /* Reduced width when collapsed */
        transition: width 0.3s ease;
    }

    /* Hide the text labels when collapsed */
    .sidebar.collapsed .navSide .label {
        display: none;
    }

    /* Adjust the logo size when sidebar is collapsed */
    .sidebar.collapsed .logo {
        width: 40px;
        /* Adjust logo size when collapsed */
        height: 40px;
    }

    /* Adjust padding for the main content when sidebar is collapsed */
    .main-content {
        margin-left: 80px;
        /* Adjust content position when sidebar is collapsed */
        transition: margin-left 0.3s ease;
    }

    /* Sidebar links - show icons but hide text when collapsed */
    .sidebar.collapsed .navSide a .label {
        display: none;
        /* Hide text */
    }

    .sidebar.collapsed .navSide a i {
        font-size: 1.5rem;
        /* Increase icon size when collapsed */
        text-align: center;
        width: 100%;
        padding: 10px;
    }

    /* Optionally, you can make the icon buttons centered when collapsed */
    .sidebar.collapsed .navSide a {
        justify-content: center;
    }

    /* Logo size and behavior */
    .sidebar.collapsed .logo {
        width: 40px;
        /* Smaller logo size */
        height: 40px;
        margin: 10px auto;
        /* Center logo in collapsed sidebar */
    }

    /* Add some space around the logo when the sidebar is collapsed */
    .sidebar.collapsed .brand {
        justify-content: center;
        margin-top: 10px;
    }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="app flex h-screen">

        <!-- Sidebar Toggle Button (Mobile) -->
        <button id="sidebar-toggle"
            class="lg:hidden fixed top-3 left-3 z-50 p-2 bg-indigo-600 text-white rounded-lg shadow-lg">
            <i class="fas fa-bars"></i>
        </button>

        <!-- SIDEBAR -->
        <aside id="sidebar"
            class="sidebar glass-sidebar w-60 fixed lg:relative z-40 h-full flex-shrink-0 flex flex-col transition-all duration-300 transform -translate-x-full lg:translate-x-0 border-r border-gray-300 rounded-r-2xl lg:rounded-none shadow-xl lg:shadow-none p-4">

            <!-- Brand Section -->
            <div class="brand flex items-center gap-3 pb-4 border-b border-gray-300" role="banner">
                <img src="https://placehold.co/40x40/6366f1/ffffff?text=B" alt="Thy Logo"
                    class="logo w-10 h-10 object-cover rounded-full shadow-md ring-2 ring-white">

                <div class="brand-text">
                    <h1 class="font-bold text-lg text-indigo-600 tracking-tight">USER Verification</h1>
                    <div class="text-xs text-gray-500">Admin Dashboard</div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="navSide flex flex-col gap-1 mt-6 flex-grow" aria-label="Main">
                <a href="#"
                    class="nav-link active bg-indigo-50 text-indigo-700 font-semibold p-3 rounded-xl flex items-center gap-3 transition-colors hover:bg-indigo-100"
                    data-target="overview">
                    <i class="fas fa-home text-lg"></i>
                    <span class="label">Overview</span>
                </a>
                <a href="#"
                    class="nav-link text-gray-700 font-medium p-3 rounded-xl flex items-center gap-3 transition-colors hover:bg-indigo-50 hover:text-indigo-600"
                    data-target="users">
                    <i class="fas fa-user text-lg"></i>
                    <span class="label">Users</span>
                </a>
                <a href="#"
                    class="nav-link text-gray-700 font-medium p-3 rounded-xl flex items-center gap-3 transition-colors hover:bg-indigo-50 hover:text-indigo-600"
                    data-target="reports">
                    <i class="fas fa-chart-bar text-lg"></i>
                    <span class="label">Reports</span>
                </a>
                <a href="#"
                    class="nav-link text-gray-700 font-medium p-3 rounded-xl flex items-center gap-3 transition-colors hover:bg-indigo-50 hover:text-indigo-600"
                    data-target="settings">
                    <i class="fas fa-cog text-lg"></i>
                    <span class="label">Settings</span>
                </a>
                <a href="#"
                    class="nav-link text-gray-700 font-medium p-3 rounded-xl flex items-center gap-3 transition-colors hover:bg-indigo-50 hover:text-indigo-600"
                    data-target="help">
                    <i class="fas fa-question-circle text-lg"></i>
                    <span class="label">Help</span>
                </a>
            </nav>

            <button id="sidebar-toggle-desktop" class="sidebar-desktop-toggle-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
            <!-- Spacer - Pushes footer down -->
            <div class="spacer flex-grow"></div>

            <!-- Footer (Placed inside sidebar for fixed width) -->
            <footer class="footer pt-4 border-t border-gray-300 mt-4">
                <div class="text-sm font-semibold text-gray-500">© <strong class="text-indigo-600">RUT24</strong> 2025
                </div>
            </footer>
        </aside>

        <!-- MAIN CONTENT CONTAINER -->
        <div class="flex flex-col flex-1 overflow-hidden">

            <!-- TOPBAR (Nav) -->
            <div class="nav flex justify-end items-center h-8 bg-white shadow-sm px-0 flex-shrink-0">
                <!-- User Profile Dropdown -->
                <div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
                    <button id="userDropdown" type="button"
                        class="inline-flex justify-center items-center h-8 p-2 text-sm font-medium text-gray-700 bg-white rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                        aria-expanded="false" aria-haspopup="true">
                        <!-- Replaced PHP with static placeholder -->
                        <?php echo htmlspecialchars($username); ?>
                        <img class="img-profile rounded-circle ms-2" src="../assets/img/profile.svg" alt="User Avatar"
                            width="32" height="32">
                    </button>

                    <!-- Dropdown menu (using placeholder JS for interaction) -->
                    <div id="userToggleMenu"
                        class="absolute right-0 mt-2 w-56 origin-top-right bg-white divide-y divide-gray-100 rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 hidden opacity-0 scale-95 transition ease-out duration-100"
                        role="menu" aria-orientation="vertical" aria-labelledby="userDropdown">
                        <div class="py-1" role="none">
                            <a href="#"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 rounded-t-xl transition duration-150"
                                role="menuitem">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-indigo-400"></i> Profile
                            </a>
                            <a href="#"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition duration-150"
                                role="menuitem">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-indigo-400"></i> Settings
                            </a>
                            <a href="#"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition duration-150"
                                role="menuitem">
                                <i class="fas fa-list fa-sm fa-fw mr-2 text-indigo-400"></i> Activity Log
                            </a>
                        </div>
                        <div class="py-1 border-t" role="none">
                            <a href="#" id="theme-toggle"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition duration-150"
                                role="menuitem">
                                <i class="fas fa-moon theme-icon fa-sm fa-fw mr-2 text-gray-600"></i> Dark Mode
                            </a>
                        </div>
                        <div class="py-1 border-t" role="none">
                            <a href="#"
                                class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-b-xl transition duration-150"
                                role="menuitem" onclick="showModal()">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT AREA -->
            <main class="main-content flex-1 p-6 overflow-y-auto">
                <h2 class="text-3xl font-extrabold text-gray-800 mb-6 border-b pb-2">Overview</h2>
                <!-- Dashboard Content Goes Here -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Example Card -->
                    <div class="bg-white p-6 rounded-xl shadow-lg transition duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-500">Total Users</p>
                            <i class="fas fa-users text-2xl text-indigo-400"></i>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 mt-2">1,234</p>
                    </div>
                    <!-- Example Card -->
                    <div class="bg-white p-6 rounded-xl shadow-lg transition duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-500">Pending Reviews</p>
                            <i class="fas fa-hourglass-half text-2xl text-yellow-400"></i>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 mt-2">42</p>
                    </div>
                    <!-- Example Card -->
                    <div class="bg-white p-6 rounded-xl shadow-lg transition duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-500">New Reports</p>
                            <i class="fas fa-flag text-2xl text-red-400"></i>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 mt-2">5</p>
                    </div>
                    <!-- Example Card -->
                    <div class="bg-white p-6 rounded-xl shadow-lg transition duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-500">Active Sessions</p>
                            <i class="fas fa-globe text-2xl text-green-400"></i>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 mt-2">87</p>
                    </div>
                </div>

                <!-- Placeholder for dynamic content -->
                <div class="mt-8 bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Latest Activity Log</h3>
                    <p class="text-gray-500">Content for the selected navigation link will appear here.</p>
                </div>

            </main>
        </div>
    </div>

    <!-- Custom Modal Structure (Replaces Bootstrap Modal) -->
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-sm transform transition-all duration-300">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Ready to Leave?</h3>
            <p class="text-gray-600 mb-6">Select "Logout" below if you are ready to end your current session.</p>
            <div class="flex justify-end space-x-3">
                <button onclick="hideModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                <button onclick="console.log('Logging out...')"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Logout</button>
            </div>
        </div>
    </div>

    <script>
    // Get the button and sidebar elements
    const toggleButton = document.getElementById('sidebar-toggle-desktop');
    const sidebar = document.querySelector('.sidebar'); // Assuming .sidebar is the sidebar element

    // Event listener for the button
    toggleButton.addEventListener('click', function() {
        // Toggle the 'collapsed' class on the sidebar
        sidebar.classList.toggle('collapsed');

        // Change the icon depending on the sidebar state
        if (sidebar.classList.contains('collapsed')) {
            // Change to a "chevron-right" when collapsed
            toggleButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
        } else {
            // Change to a "chevron-left" when expanded
            toggleButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
        }
    });
    </script>


    <!-- <script>
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
            viewContainers.overview.classList.remove('hidden');
        } else if (target === 'users') {
            viewContainers.users.classList.remove('hidden');
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
    toggleButtonDesktop.addEventListener('click', () => {
        const isCollapsed = sidebar.classList.toggle('sidebar-collapsed');
        appWrapper.classList.toggle('collapsed', isCollapsed);

        // Toggle chevron icon direction
        const icon = toggleButtonDesktop.querySelector('.fas');
        icon.classList.toggle('fa-chevron-left', !isCollapsed);
        icon.classList.toggle('fa-chevron-right', isCollapsed);
    });


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
    function showModal() {
        logoutModal.classList.remove('hidden');
    }

    function hideModal() {
        logoutModal.classList.add('hidden');
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
    </script> -->

    <!-- Bootstrap 5 JS is not needed since we manually implemented the dropdown and modal -->
</body>

</html>