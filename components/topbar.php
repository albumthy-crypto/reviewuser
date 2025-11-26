 <!-- TOPBAR -->
      <header class="topbar" role="banner">
        <div style="display: flex; align-items: center; gap: 12px">
          <button class="btn" id="sidebar-toggle" aria-label="Toggle sidebar">
            ☰
          </button>
          <div class="search" aria-hidden="false">
            <svg
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              style="opacity: 0.8"
            >
              <path
                d="M21 21l-4.35-4.35"
                stroke="#374151"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <circle
                cx="11"
                cy="11"
                r="5"
                stroke="#374151"
                stroke-width="1.6"
              />
            </svg>
            <input
              type="search"
              placeholder="Search users, reports..."
              aria-label="Search"
            />
          </div>
        </div>

        <div class="top-actions">
        <?php include '../components/toggleUserMenu.php'; ?>
        </div>
        
      </header>