/* ==========================
      IR-KVKS LOGIN JS
========================== */

document.addEventListener("DOMContentLoaded", function () {

    /* ==========================
       Auto Focus
    ========================== */

    const username = document.querySelector("input[name='username']");

    if (username) {
        username.focus();
    }


    /* ==========================
       Show / Hide Password
    ========================== */

    const password = document.getElementById("password");
    const toggle = document.getElementById("togglePassword");

    if (toggle && password) {

        toggle.addEventListener("click", function () {

            if (password.type === "password") {

                password.type = "text";

                toggle.classList.remove("fa-eye-slash");
                toggle.classList.add("fa-eye");

            } else {

                password.type = "password";

                toggle.classList.remove("fa-eye");
                toggle.classList.add("fa-eye-slash");

            }

        });

    }


    /* ==========================
       Role Card Animation
    ========================== */

    const cards = document.querySelectorAll(".role-card");

    cards.forEach(card => {

        card.addEventListener("click", function () {

            cards.forEach(c => c.classList.remove("selected"));

            this.classList.add("selected");

        });

    });


    /* ==========================
       Form Validation
    ========================== */

    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {

        const username = document.querySelector("input[name='username']").value.trim();

        const password = document.querySelector("input[name='password']").value.trim();

        const role = document.querySelector("input[name='role']:checked");

        if (username === "") {

            alert("Sila masukkan ID Pengguna.");

            e.preventDefault();

            return;

        }

        if (password === "") {

            alert("Sila masukkan Kata Laluan.");

            e.preventDefault();

            return;

        }

        if (!role) {

            alert("Sila pilih role.");

            e.preventDefault();

            return;

        }

    });


    /* ==========================
       Fade In Animation
    ========================== */

    const card = document.querySelector(".login-card");

    card.style.opacity = "0";
    card.style.transform = "translateY(30px)";

    setTimeout(() => {

        card.style.transition = ".6s ease";

        card.style.opacity = "1";
        card.style.transform = "translateY(0px)";

    }, 200);

});