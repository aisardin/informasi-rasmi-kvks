/* =====================================================
   IR-KVKS DARK MODE SYSTEM
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    const darkModeToggle = document.getElementById("darkMode");

    /* =========================================
       LOAD SAVED DARK MODE
    ========================================= */

    const savedTheme = localStorage.getItem("irkvks-theme");

    if (savedTheme === "dark") {

        document.body.classList.add("dark-mode");

        if (darkModeToggle) {
            darkModeToggle.checked = true;
        }

    } else {

        document.body.classList.remove("dark-mode");

        if (darkModeToggle) {
            darkModeToggle.checked = false;
        }

    }


    /* =========================================
       DARK MODE TOGGLE
    ========================================= */

    if (darkModeToggle) {

        darkModeToggle.addEventListener("change", function () {

            if (this.checked) {

                // Aktifkan dark mode
                document.body.classList.add("dark-mode");

                localStorage.setItem(
                    "irkvks-theme",
                    "dark"
                );

            } else {

                // Kembali kepada tema asal
                document.body.classList.remove("dark-mode");

                localStorage.removeItem(
                    "irkvks-theme"
                );

            }

        });

    }

});