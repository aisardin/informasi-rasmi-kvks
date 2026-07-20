document.addEventListener("DOMContentLoaded", function(){

    const sidebar = document.getElementById("sidebar");
    const toggle = document.querySelector(".toggle");

    toggle.addEventListener("click", function(){

        sidebar.classList.toggle("close");

    });

});