document.addEventListener("DOMContentLoaded", function () {

    const sidebar =
        document.getElementById("adminSidebar");

    const toggleBtn =
        document.getElementById("adminToggleBtn");

    const overlay =
        document.querySelector(".admin-sidebar-overlay");


    /* =========================================
       CHECK ELEMENT
    ========================================= */

    if (!sidebar || !toggleBtn) {
        return;
    }


    /* =========================================
       TOGGLE SIDEBAR
    ========================================= */

    toggleBtn.addEventListener("click", function () {

        sidebar.classList.toggle("show");

        if (overlay) {

            overlay.classList.toggle("active");

        }

    });


    /* =========================================
       OVERLAY CLICK
    ========================================= */

    if (overlay) {

        overlay.addEventListener("click", function () {

            sidebar.classList.remove("show");

            overlay.classList.remove("active");

        });

    }


    /* =========================================
       CLOSE SIDEBAR
       APABILA MENU DIKLIK
    ========================================= */

    const menuLinks =
        document.querySelectorAll(".admin-nav-link");


    menuLinks.forEach(function (link) {

        link.addEventListener("click", function () {

            if (window.innerWidth <= 992) {

                sidebar.classList.remove("show");

                if (overlay) {

                    overlay.classList.remove("active");

                }

            }

        });

    });


    /* =========================================
       RESIZE
    ========================================= */

    window.addEventListener("resize", function () {

        if (window.innerWidth > 992) {

            sidebar.classList.remove("show");

            if (overlay) {

                overlay.classList.remove("active");

            }

        }

    });

});