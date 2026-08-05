document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleBtn");
    const overlay = document.querySelector(".sidebar-overlay");

    toggleBtn.addEventListener("click", function () {

        sidebar.classList.toggle("show");
        overlay.classList.toggle("active");

    });

    overlay.addEventListener("click", function () {

        sidebar.classList.remove("show");
        overlay.classList.remove("active");

    });

});