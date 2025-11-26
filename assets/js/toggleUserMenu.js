document.addEventListener("DOMContentLoaded", function () {
  const toggle = document.getElementById("userToggle");
  const menu = document.getElementById("userDropdownMenu");

  toggle.addEventListener("click", function () {
    menu.classList.toggle("show");
  });

  document.addEventListener("click", function (e) {
    if (!toggle.contains(e.target) && !menu.contains(e.target)) {
      menu.classList.remove("show");
    }
  });
});

