<?php

/* =================================================
   SESSION
================================================= */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =================================================
   ADMIN ACCESS
================================================= */

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: ../login.php");
    exit();
}


/* =================================================
   DATABASE
================================================= */

include("../../components/config.php");


/* =================================================
   ADMIN DATA
================================================= */

$user_id = (int) $_SESSION['user_id'];

$userQuery = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id = $user_id LIMIT 1"
);

$user = mysqli_fetch_assoc($userQuery);


/* =================================================
   UPDATE SESSION NAMA
================================================= */

if ($user) {

    $_SESSION['nama'] = $user['nama'];

}


/* =================================================
   STATISTICS
================================================= */


/* JUMLAH PELAJAR */

$resultPelajar = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS jumlah 
     FROM users 
     WHERE role = 'pelajar'"
);

$dataPelajar = mysqli_fetch_assoc($resultPelajar);

$jumlahPelajar = $dataPelajar['jumlah'] ?? 0;


/* JUMLAH PENSYARAH */

$resultPensyarah = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS jumlah 
     FROM users 
     WHERE role = 'pensyarah'"
);

$dataPensyarah = mysqli_fetch_assoc($resultPensyarah);

$jumlahPensyarah = $dataPensyarah['jumlah'] ?? 0;


/* JUMLAH KELAS */

$resultKelas = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS jumlah 
     FROM kelas"
);

$dataKelas = mysqli_fetch_assoc($resultKelas);

$jumlahKelas = $dataKelas['jumlah'] ?? 0;


/* JUMLAH PENGUMUMAN */

$resultPengumuman = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS jumlah 
     FROM pengumuman"
);

$dataPengumuman = mysqli_fetch_assoc($resultPengumuman);

$jumlahPengumuman = $dataPengumuman['jumlah'] ?? 0;

/* =================================================
   PENGUMUMAN TERKINI
================================================= */

$latestPengumuman = mysqli_query(
    $conn,
    "
    SELECT
        id,
        tajuk,
        kandungan,
        sasaran,
        status,
        tarikh_cipta
    FROM pengumuman
    ORDER BY tarikh_cipta DESC
    LIMIT 5
    "
);


/* =================================================
   LATEST AKTIVITI
================================================= */

$latestAktiviti = mysqli_query(
    $conn,
    "SELECT *
     FROM aktiviti
     ORDER BY tarikh DESC
     LIMIT 5"
);


/* =================================================
   CSS
================================================= */

$pageCss = "admin-dashboard.css";

include("../../asset/admin/admin-components/adminHead.php");

?>

<!-- =================================================
     SIDEBAR
================================================= -->

<?php

include("../../asset/admin/admin-components/admin-sidebar.php");

?>


<!-- =================================================
     SIDEBAR OVERLAY
================================================= -->

<div class="admin-sidebar-overlay"></div>


<!-- =================================================
     MAIN
================================================= -->

<div class="admin-main">


    <!-- =================================================
         TOPBAR
    ================================================== -->

    <?php

    include("../../asset/admin/admin-components/admin-topbar.php");

    ?>


    <!-- =================================================
         PAGE HEADER
    ================================================== -->

    <div class="page-header">

        <div>

            <span class="page-badge">

                <i class="fa-solid fa-shield-halved"></i>

                Admin

            </span>


            <h1>
                Dashboard
            </h1>


            <p>

                Selamat datang kembali,

                <strong>
                    <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?>
                </strong>.

            </p>

        </div>

    </div>


    <!-- =================================================
         STATISTICS
    ================================================== -->

    <div class="admin-stat-grid">


        <!-- PELAJAR -->

        <div class="admin-stat-card blue">

            <div class="admin-stat-icon">

                <i class="fa-solid fa-user-graduate"></i>

            </div>

            <div class="admin-stat-info">

                <span>
                    Jumlah Pelajar
                </span>

                <strong>
                    <?= $jumlahPelajar ?>
                </strong>

                <small>
                    Pelajar berdaftar
                </small>

            </div>

        </div>


        <!-- PENSYARAH -->

        <div class="admin-stat-card green">

            <div class="admin-stat-icon">

                <i class="fa-solid fa-chalkboard-user"></i>

            </div>

            <div class="admin-stat-info">

                <span>
                    Jumlah Pensyarah
                </span>

                <strong>
                    <?= $jumlahPensyarah ?>
                </strong>

                <small>
                    Pensyarah berdaftar
                </small>

            </div>

        </div>


        <!-- KELAS -->

        <div class="admin-stat-card purple">

            <div class="admin-stat-icon">

                <i class="fa-solid fa-book-open"></i>

            </div>

            <div class="admin-stat-info">

                <span>
                    Jumlah Kelas
                </span>

                <strong>
                    <?= $jumlahKelas ?>
                </strong>

                <small>
                    Kelas tersedia
                </small>

            </div>

        </div>


        <!-- PENGUMUMAN -->

        <div class="admin-stat-card orange">

            <div class="admin-stat-icon">

                <i class="fa-solid fa-bullhorn"></i>

            </div>

            <div class="admin-stat-info">

                <span>
                    Pengumuman
                </span>

                <strong>
                    <?= $jumlahPengumuman ?>
                </strong>

                <small>
                    Pengumuman diterbitkan
                </small>

            </div>

        </div>


    </div>


    <!-- =================================================
         CONTENT GRID
    ================================================== -->

    <div class="admin-dashboard-grid">


        <!-- =================================================
             PENGUMUMAN TERKINI
        ================================================== -->

<!-- =================================================
     PENGUMUMAN TERKINI
================================================== -->

<div class="admin-card">

    <div class="admin-card-header">

        <div>

            <h2>
                Pengumuman Terkini
            </h2>

            <p>
                Pengumuman yang baru ditambahkan.
            </p>

        </div>


        <a
            href="pengumuman/index.php"
            class="view-all-btn"
        >

            Lihat Semua

            <i class="fa-solid fa-arrow-right"></i>

        </a>

    </div>


    <div class="admin-list">


        <?php if (
            $latestPengumuman &&
            mysqli_num_rows($latestPengumuman) > 0
        ): ?>


            <?php while (
                $pengumuman =
                mysqli_fetch_assoc($latestPengumuman)
            ): ?>


                <div class="admin-list-item">


                    <!-- ICON -->

                    <div class="list-icon blue">

                        <i class="fa-solid fa-bullhorn"></i>

                    </div>


                    <!-- CONTENT -->

                    <div class="list-content">


                        <!-- TAJUK -->

                        <h3>

                            <?= htmlspecialchars(
                                $pengumuman['tajuk']
                                ?? 'Pengumuman'
                            ) ?>

                        </h3>


                        <!-- KANDUNGAN -->

                        <p>

                            <?= htmlspecialchars(
                                mb_strimwidth(
                                    $pengumuman['kandungan'] ?? '',
                                    0,
                                    100,
                                    '...'
                                )
                            ) ?>

                        </p>


                        <!-- INFO -->

                        <small>

                            <i class="fa-regular fa-calendar"></i>

                            <?php

                            if (
                                !empty(
                                    $pengumuman['tarikh_cipta']
                                )
                            ) {

                                echo date(
                                    'd M Y',
                                    strtotime(
                                        $pengumuman['tarikh_cipta']
                                    )
                                );

                            } else {

                                echo 'Tarikh tidak tersedia';

                            }

                            ?>

                            &nbsp; • &nbsp;

                            <i class="fa-solid fa-users"></i>

                            <?php

                            if (
                                $pengumuman['sasaran']
                                === 'semua'
                            ) {

                                echo 'Semua';

                            } elseif (
                                $pengumuman['sasaran']
                                === 'pelajar'
                            ) {

                                echo 'Pelajar';

                            } elseif (
                                $pengumuman['sasaran']
                                === 'pensyarah'
                            ) {

                                echo 'Pensyarah';

                            } else {

                                echo '-';

                            }

                            ?>

                        </small>


                    </div>


                    <!-- STATUS -->

                    <div class="list-status">

                        <?php if (
                            $pengumuman['status']
                            === 'aktif'
                        ): ?>

                            <span class="status active">
                                Aktif
                            </span>

                        <?php else: ?>

                            <span class="status inactive">
                                Tidak Aktif
                            </span>

                        <?php endif; ?>

                    </div>


                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <!-- =================================================
                 TIADA PENGUMUMAN
            ================================================== -->

            <div class="admin-empty">

                <div class="empty-icon">

                    <i class="fa-solid fa-bullhorn"></i>

                </div>


                <h3>
                    Tiada pengumuman
                </h3>


                <p>
                    Belum terdapat pengumuman dalam sistem.
                </p>


                <a
                    href="pengumuman/tambah.php"
                    class="view-all-btn"
                >

                    <i class="fa-solid fa-plus"></i>

                    Tambah Pengumuman

                </a>

            </div>


        <?php endif; ?>


    </div>

</div>

        <!-- =================================================
             AKTIVITI TERKINI
        ================================================== -->

        <div class="admin-card">

            <div class="admin-card-header">

                <div>

                    <h2>
                        Aktiviti Terkini
                    </h2>

                    <p>
                        Aktiviti yang akan berlangsung.
                    </p>

                </div>


                <a
                    href="aktiviti/index.php"
                    class="view-all-btn"
                >

                    Lihat Semua

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>


            <div class="admin-list">


                <?php if (
                    $latestAktiviti &&
                    mysqli_num_rows($latestAktiviti) > 0
                ): ?>


                    <?php while (
                        $aktiviti =
                        mysqli_fetch_assoc($latestAktiviti)
                    ): ?>


                        <div class="admin-list-item">


                            <div class="list-icon green">

                                <i class="fa-solid fa-calendar-days"></i>

                            </div>


                            <div class="list-content">

                                <h3>

                                    <?= htmlspecialchars(
                                        $aktiviti['nama_aktiviti']
                                        ?? 'Aktiviti'
                                    ) ?>

                                </h3>


                                <p>

                                    <?= !empty($aktiviti['tarikh'])

                                        ? date(
                                            "d M Y",
                                            strtotime(
                                                $aktiviti['tarikh']
                                            )
                                        )

                                        : "Tarikh belum ditetapkan"

                                    ?>

                                </p>

                            </div>


                        </div>


                    <?php endwhile; ?>


                <?php else: ?>


                    <div class="admin-empty">

                        <i class="fa-solid fa-calendar-xmark"></i>

                        <h3>
                            Tiada aktiviti
                        </h3>

                        <p>
                            Belum terdapat aktiviti.
                        </p>

                    </div>


                <?php endif; ?>


            </div>

        </div>


    </div>


    <!-- =================================================
         QUICK ACTION
    ================================================== -->

    <div class="admin-card quick-actions-card">


        <div class="admin-card-header">

            <div>

                <h2>
                    Tindakan Pantas
                </h2>

                <p>
                    Akses fungsi pengurusan dengan cepat.
                </p>

            </div>

        </div>


        <div class="quick-actions">


            <a
                href="pengguna/tambah.php"
                class="admin-action blue"
            >

                <i class="fa-solid fa-user-plus"></i>

                <span>
                    Tambah Pengguna
                </span>

            </a>


            <a
                href="pengumuman/tambah.php"
                class="admin-action purple"
            >

                <i class="fa-solid fa-bullhorn"></i>

                <span>
                    Tambah Pengumuman
                </span>

            </a>


            <a
                href="aktiviti/tambah.php"
                class="admin-action green"
            >

                <i class="fa-solid fa-calendar-plus"></i>

                <span>
                    Tambah Aktiviti
                </span>

            </a>


            <a
                href="akademik/tambah.php"
                class="admin-action orange"
            >

                <i class="fa-solid fa-book-medical"></i>

                <span>
                    Tambah Kelas
                </span>

            </a>


        </div>

    </div>


</div>

</body>
</html>