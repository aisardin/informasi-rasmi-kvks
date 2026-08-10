<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar" id="sidebar">

    <!-- ======================
            LOGO
    ======================= -->

    <div>

        <div class="logo">

            <img src="<?= $baseUrl ?>asset/images/logo kv.png"
                 class="logo-img"
                 alt="IR-KVKS">

            <div class="logo-text">

                <h2>IR-KVKS</h2>

                <span>Sistem Perkongsian Maklumat</span>

            </div>

        </div>

        <!-- ======================
                MENU
        ======================= -->

        <nav class="menu">

            <a href="<?= $baseUrl ?>pages/pelajar/dashboard.php"
                class="<?= $currentPage=="dashboard.php" ? "active":"";?>">

                <i class="fa-solid fa-house"></i>

                <span>Dashboard</span>

            </a>

            <a href="<?= $baseUrl ?>pages/pelajar/pengumuman.php"
                class="<?= $currentPage=="pengumuman.php" ? "active":"";?>">

                <i class="fa-solid fa-bullhorn"></i>

                <span>Pengumuman</span>

            </a>

            <a href="<?= $baseUrl ?>pages/pelajar/akademik.php"
                class="<?= $currentPage=="akademik.php" ? "active":"";?>">

                <i class="fa-solid fa-book"></i>

                <span>Akademik</span>

            </a>

            <a href="<?= $baseUrl ?>pages/pelajar/aktiviti.php"
                class="<?= $currentPage=="aktiviti.php" ? "active":"";?>">

                <i class="fa-solid fa-calendar-days"></i>

                <span>Aktiviti</span>

            </a>

            <a href="<?= $baseUrl ?>pages/pelajar/profil.php"
                class="<?= $currentPage=="profil.php" ? "active":"";?>">

                <i class="fa-solid fa-user"></i>

                <span>Profil</span>

            </a>

            <a href="<?= $baseUrl ?>pages/pelajar/tetapan.php">

                <i class="fa-solid fa-gear"></i>

                <span>Tetapan</span>

            </a>

        </nav>

    </div>

    <!-- ======================
            LOGOUT
    ======================= -->

    <div class="logout">

        <a href="<?= $baseUrl ?>auth/logout.php">

            <i class="fa-solid fa-right-from-bracket"></i>

            <span>Log Keluar</span>

        </a>

    </div>

</aside>