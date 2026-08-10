<?php
session_start();

// =====================================
// SEMAK LOGIN
// =====================================
if (!isset($_SESSION['role']) || $_SESSION['role'] != "pelajar") {
    header("Location: ../../pages/login.php");
    exit();
}

$baseUrl = "../../";

// =====================================
// DATABASE
// =====================================
include(__DIR__ . "/../../components/config.php");

// =====================================
// CSS KHUSUS
// =====================================
$pageCss = "tetapan.css";

// =====================================
// DAPATKAN MAKLUMAT PENGGUNA
// =====================================
$userId = $_SESSION['user_id'];

$sqlUser = "
    SELECT
        id,
        nama,
        email,
        role
    FROM users
    WHERE id = ?
    LIMIT 1
";

$stmtUser = mysqli_prepare($conn, $sqlUser);

if (!$stmtUser) {
    die("Ralat SQL: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmtUser, "i", $userId);
mysqli_stmt_execute($stmtUser);

$resultUser = mysqli_stmt_get_result($stmtUser);

$user = mysqli_fetch_assoc($resultUser);

mysqli_stmt_close($stmtUser);

if (!$user) {
    session_destroy();

    header("Location: ../../pages/login.php");
    exit();
}


// =====================================
// HEADER
// =====================================
$pageCss = "tetapan.css";
include("../../components/header.php");

// =====================================
// SIDEBAR
// =====================================
include("../../components/sidebar.php");
?>


<body>

<div class="main-content" id="mainContent">


    <!-- =====================================
         TOPBAR
    ====================================== -->

    <?php include("../../components/topbar.php"); ?>


    <!-- =====================================
         PAGE HEADER
    ====================================== -->

    <div class="page-header">

        <div>

            <span class="page-badge">

                <i class="fa-solid fa-gear"></i>

                Tetapan

            </span>

            <h1>Tetapan</h1>

            <p>
                Urus tetapan dan pilihan akaun anda.
            </p>

        </div>

    </div>


    <!-- =====================================
         SETTINGS CONTENT
    ====================================== -->

    <div class="settings-content">


        <!-- =================================
             AKAUN
        ================================== -->

        <div class="settings-card">

            <div class="settings-card-header">

                <div class="settings-heading-icon blue">

                    <i class="fa-solid fa-user"></i>

                </div>

                <div>

                    <h2>Akaun</h2>

                    <p>
                        Maklumat dan pengurusan akaun anda.
                    </p>

                </div>

            </div>


            <div class="settings-list">


                <!-- PROFIL -->

                <a
                    href="profil.php"
                    class="setting-item"
                >

                    <div class="setting-icon">

                        <i class="fa-solid fa-id-card"></i>

                    </div>

                    <div class="setting-info">

                        <h3>Profil Saya</h3>

                        <p>
                            Lihat dan kemas kini maklumat profil anda.
                        </p>

                    </div>

                    <i class="fa-solid fa-chevron-right setting-arrow"></i>

                </a>


                <!-- PASSWORD -->

                <a
                    href="tukar-password.php"
                    class="setting-item"
                >

                    <div class="setting-icon">

                        <i class="fa-solid fa-lock"></i>

                    </div>

                    <div class="setting-info">

                        <h3>Kata Laluan</h3>

                        <p>
                            Tukar kata laluan akaun anda.
                        </p>

                    </div>

                    <i class="fa-solid fa-chevron-right setting-arrow"></i>

                </a>


            </div>

        </div>



        <!-- =================================
             NOTIFIKASI
        ================================== -->

        <div class="settings-card">

            <div class="settings-card-header">

                <div class="settings-heading-icon purple">

                    <i class="fa-solid fa-bell"></i>

                </div>

                <div>

                    <h2>Notifikasi</h2>

                    <p>
                        Kawal notifikasi yang anda terima.
                    </p>

                </div>

            </div>


            <div class="settings-list">


                <!-- PENGUMUMAN -->

                <div class="setting-item toggle-item">

                    <div class="setting-icon purple-bg">

                        <i class="fa-solid fa-bullhorn"></i>

                    </div>

                    <div class="setting-info">

                        <h3>Pengumuman Baharu</h3>

                        <p>
                            Terima pemberitahuan apabila terdapat
                            pengumuman baharu.
                        </p>

                    </div>


                    <label class="switch">

                        <input
                            type="checkbox"
                            id="announcementNotification"
                            checked
                        >

                        <span class="slider"></span>

                    </label>

                </div>


                <!-- TUGASAN -->

                <div class="setting-item toggle-item">

                    <div class="setting-icon green-bg">

                        <i class="fa-solid fa-clipboard-check"></i>

                    </div>

                    <div class="setting-info">

                        <h3>Tugasan</h3>

                        <p>
                            Terima pemberitahuan berkaitan tugasan
                            dan tarikh akhir.
                        </p>

                    </div>


                    <label class="switch">

                        <input
                            type="checkbox"
                            id="taskNotification"
                            checked
                        >

                        <span class="slider"></span>

                    </label>

                </div>


                <!-- AKTIVITI -->

                <div class="setting-item toggle-item">

                    <div class="setting-icon orange-bg">

                        <i class="fa-solid fa-calendar-days"></i>

                    </div>

                    <div class="setting-info">

                        <h3>Aktiviti</h3>

                        <p>
                            Terima pemberitahuan tentang aktiviti
                            dan program yang akan datang.
                        </p>

                    </div>


                    <label class="switch">

                        <input
                            type="checkbox"
                            id="activityNotification"
                            checked
                        >

                        <span class="slider"></span>

                    </label>

                </div>


            </div>

        </div>



        <!-- =================================
             PAPARAN
        ================================== -->

        <div class="settings-card">

            <div class="settings-card-header">

                <div class="settings-heading-icon dark">

                    <i class="fa-solid fa-display"></i>

                </div>

                <div>

                    <h2>Paparan</h2>

                    <p>
                        Laraskan pengalaman paparan sistem.
                    </p>

                </div>

            </div>


            <div class="settings-list">


                <!-- MODE GELAP -->

                <div class="setting-item toggle-item">

                    <div class="setting-icon">

                        <i class="fa-solid fa-moon"></i>

                    </div>

                    <div class="setting-info">

                        <h3>Mod Gelap</h3>

                        <p>
                            Gunakan tema gelap untuk paparan sistem.
                        </p>

                    </div>


                    <label class="switch">

                        <input
                            type="checkbox"
                            id="darkMode"
                        >

                        <span class="slider"></span>

                    </label>

                </div>


            </div>

        </div>



        <!-- =================================
             LOG KELUAR
        ================================== -->

        <div class="logout-card">

            <div class="logout-icon">

                <i class="fa-solid fa-right-from-bracket"></i>

            </div>


            <div class="logout-info">

                <h3>Log Keluar</h3>

                <p>
                    Log keluar daripada akaun IR-KVKS anda.
                </p>

            </div>


            <a
                href="../../auth/logout.php"
                class="logout-btn"
                onclick="return confirm('Adakah anda pasti mahu log keluar?');"
            >

                <i class="fa-solid fa-right-from-bracket"></i>

                Log Keluar

            </a>

        </div>


    </div>

</div>


<div class="sidebar-overlay"></div>


<!-- =====================================
     JAVASCRIPT
====================================== -->

<script>

document.addEventListener("DOMContentLoaded", function () {

    /* =================================
       DARK MODE
    ================================= */

    const darkMode = document.getElementById("darkMode");

    if (localStorage.getItem("darkMode") === "true") {

        darkMode.checked = true;

        document.body.classList.add("dark-mode");

    }


    darkMode.addEventListener("change", function () {

        if (this.checked) {

            document.body.classList.add("dark-mode");

            localStorage.setItem("darkMode", "true");

        } else {

            document.body.classList.remove("dark-mode");

            localStorage.setItem("darkMode", "false");

        }

    });


    /* =================================
       NOTIFICATION SETTINGS
    ================================= */

    const announcementNotification =
        document.getElementById("announcementNotification");

    const taskNotification =
        document.getElementById("taskNotification");

    const activityNotification =
        document.getElementById("activityNotification");


    announcementNotification.checked =
        localStorage.getItem("announcementNotification") !== "false";

    taskNotification.checked =
        localStorage.getItem("taskNotification") !== "false";

    activityNotification.checked =
        localStorage.getItem("activityNotification") !== "false";


    announcementNotification.addEventListener("change", function () {

        localStorage.setItem(
            "announcementNotification",
            this.checked
        );

    });


    taskNotification.addEventListener("change", function () {

        localStorage.setItem(
            "taskNotification",
            this.checked
        );

    });


    activityNotification.addEventListener("change", function () {

        localStorage.setItem(
            "activityNotification",
            this.checked
        );

    });

});

</script>


</body>
</html>