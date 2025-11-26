// Demo data (replace this with API calls)
const demoStats = {
  headOffice: 128,
  branches: 24,
  division: 12,
  department: 42,
  groupApp: 18,
  usersLogin: 56,
  reviewFile: 9,
  reviewPeriod: "Q4 2025",
};

const recentUsers = [
  {
    name: "Alice Tan",
    location: "Head Office",
    role: "Manager",
    lastLogin: "2025-10-16 08:42",
  },
  {
    name: "Budi Santoso",
    location: "Branch - KL",
    role: "Support",
    lastLogin: "2025-10-16 08:20",
  },
  {
    name: "Chen Li",
    location: "Branch - SG",
    role: "Engineer",
    lastLogin: "2025-10-15 17:59",
  },
  {
    name: "Dina R.",
    location: "Head Office",
    role: "HR",
    lastLogin: "2025-10-15 16:12",
  },
  {
    name: "Eko W.",
    location: "Branch - JB",
    role: "Sales",
    lastLogin: "2025-10-14 11:02",
  },
];

// Populate stat cards
document.getElementById("head-office-count").textContent = demoStats.headOffice;
document.getElementById("branches-count").textContent = demoStats.branches;
document.getElementById("division-count").textContent = demoStats.division;
document.getElementById("department-count").textContent = demoStats.department;
document.getElementById("group-app-count").textContent = demoStats.groupApp;
document.getElementById("users-login-count").textContent = demoStats.usersLogin;
document.getElementById("review-file-count").textContent = demoStats.reviewFile;
document.getElementById("review-period-count").textContent =
  demoStats.reviewPeriod;

// Populate recent users table
const tbody = document.getElementById("recent-users-tbody");
recentUsers.forEach((u) => {
  const tr = document.createElement("tr");
  tr.innerHTML = `
        <td><strong>${u.name}</strong></td>
        <td>${u.location}</td>
        <td>${u.role}</td>
        <td>${u.lastLogin}</td>
      `;
  tbody.appendChild(tr);
});

// Last updated timestamp
document.getElementById("last-updated").textContent =
  new Date().toLocaleString();

// Responsive sidebar toggle for small screens
const sidebar = document.querySelector(".sidebar");
const toggleBtn = document.getElementById("sidebar-toggle");
let isCollapsed = false;

toggleBtn.addEventListener("click", () => {
  if (isCollapsed) {
    sidebar.classList.remove("collapsed");
    sidebar.style.width = "260px";
    document.documentElement.style.setProperty("--sidebar-width", "260px");
  } else {
    sidebar.classList.add("collapsed");
    sidebar.style.width = "90px";
    document.documentElement.style.setProperty("--sidebar-width", "90px");
  }
  isCollapsed = !isCollapsed;
});

// Chart.js: Line Chart for demo active users
window.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("lineChart");
  const labels = generateDateLabels(12); // 12 periods (e.g., days/weeks)
  const data = {
    labels,
    datasets: [
      {
        label: "Head Office",
        data: randomWalk(12, 80, 135),
        tension: 0.25,
        borderWidth: 2,
        pointRadius: 4,
        backgroundColor: "rgba(99,102,241,0.08)",
        borderColor: "rgba(79,70,229,0.95)",
        fill: true,
        pointHoverRadius: 6,
      },
      {
        label: "Branches (avg)",
        data: randomWalk(12, 40, 75),
        tension: 0.25,
        borderWidth: 2,
        pointRadius: 3,
        backgroundColor: "rgba(34,197,94,0.06)",
        borderColor: "rgba(34,197,94,0.9)",
        fill: true,
      },
    ],
  };

  const chart = new Chart(ctx, {
    type: "line",
    data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: { boxWidth: 12, padding: 16 },
        },
        tooltip: {
          mode: "index",
          intersect: false,
        },
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { maxRotation: 0, autoSkip: true },
        },
        y: {
          grid: { color: "rgba(15,23,42,0.04)" },
          beginAtZero: true,
          suggestedMax: 160,
        },
      },
    },
  });
});
//change Them (e.g, Dark mode...)
const themeToggle = document.getElementById("theme-toggle");

themeToggle.addEventListener("click", (e) => {
  e.preventDefault(); // prevent page jump
  document.body.classList.toggle("dark-theme");
});



// Helper functions for demo
function generateDateLabels(n) {
  // Generate n labels counting backwards from today
  const labels = [];
  const d = new Date();
  for (let i = n - 1; i >= 0; i--) {
    const dt = new Date(d);
    dt.setDate(d.getDate() - i);
    labels.push(`${dt.getMonth() + 1}/${dt.getDate()}`);
  }
  return labels;
}

function randomWalk(n, min, max) {
  const arr = [];
  let cur = Math.floor((min + max) / 2);
  for (let i = 0; i < n; i++) {
    cur += Math.floor(Math.random() * 11 - 5);
    if (cur < min) cur = min + Math.floor(Math.random() * 5);
    if (cur > max) cur = max - Math.floor(Math.random() * 5);
    arr.push(cur);
  }
  return arr;
}
// heigh full screen
document.documentElement.style.setProperty(
  "--vh",
  `${window.innerHeight * 0.01}px`
);
