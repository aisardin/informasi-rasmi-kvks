<?php
session_start();

// Semak login
if (!isset($_SESSION['role']) || $_SESSION['role'] != "pelajar") {
    header("Location: ../../pages/login.php");
    exit();
}

$baseUrl = "../../";

// Database connection
include(__DIR__ . "/../../components/config.php");

// =====================================
// DAPATKAN MAKLUMAT PENGGUNA
// =====================================
$userId = $_SESSION['user_id'];

$sqlUser = "SELECT * FROM users WHERE id = ? LIMIT 1";

$stmtUser = mysqli_prepare($conn, $sqlUser);

mysqli_stmt_bind_param($stmtUser, "i", $userId);

mysqli_stmt_execute($stmtUser);

$resultUser = mysqli_stmt_get_result($stmtUser);

$user = mysqli_fetch_assoc($resultUser);

mysqli_stmt_close($stmtUser);

// =====================================
// QUERY 1: KELAS AKTIF
// =====================================
$queryKelas = "
    SELECT COUNT(DISTINCT k.id) as total
    FROM kelas k
    INNER JOIN kelas_pelajar kp ON k.id = kp.kelas_id
    WHERE kp.pelajar_id = ?
    AND k.status = 'aktif'
";

$stmtKelas = mysqli_prepare($conn, $queryKelas);
mysqli_stmt_bind_param($stmtKelas, "i", $userId);
mysqli_stmt_execute($stmtKelas);
$resultKelas = mysqli_stmt_get_result($stmtKelas);
$kelasAktif = mysqli_fetch_assoc($resultKelas)['total'];
mysqli_stmt_close($stmtKelas);

// =====================================
// QUERY 2: TUGASAN AKTIF
// =====================================
$queryTugasan = "
    SELECT COUNT(DISTINCT t.id) as total
    FROM tugasan t
    INNER JOIN kelas k ON t.kelas_id = k.id
    INNER JOIN kelas_pelajar kp ON k.id = kp.kelas_id
    LEFT JOIN tugasan_pelajar tp
        ON t.id = tp.tugasan_id
        AND tp.pelajar_id = ?
    WHERE kp.pelajar_id = ?
    AND t.status = 'aktif'
    AND t.tarikh_akhir >= NOW()
    AND (tp.status IS NULL OR tp.status = 'belum_selesai')
";

$stmtTugasan = mysqli_prepare($conn, $queryTugasan);
mysqli_stmt_bind_param($stmtTugasan, "ii", $userId, $userId);
mysqli_stmt_execute($stmtTugasan);
$resultTugasan = mysqli_stmt_get_result($stmtTugasan);
$tugasanAktif = mysqli_fetch_assoc($resultTugasan)['total'];
mysqli_stmt_close($stmtTugasan);

// =====================================
// QUERY 3: PENGUMUMAN BAHARU
// =====================================
$queryPengumuman = "
    SELECT COUNT(*) as total
    FROM pengumuman
    WHERE status='aktif'
    AND tarikh_cipta >= DATE_SUB(NOW(),INTERVAL 7 DAY)
    AND (sasaran='semua' OR sasaran='pelajar')
";

$resultPengumuman = mysqli_query($conn, $queryPengumuman);
$pengumumanBaharu = mysqli_fetch_assoc($resultPengumuman)['total'];

// =====================================
// QUERY 4: AKTIVITI AKAN DATANG
// =====================================
$queryAktiviti = "
    SELECT COUNT(*) as total
    FROM aktiviti
    WHERE tarikh >= CURDATE()
    AND status='aktif'
";

$resultAktiviti = mysqli_query($conn, $queryAktiviti);
$aktivitiAkanDatang = mysqli_fetch_assoc($resultAktiviti)['total'];

// =====================================
// QUERY 5 : BERITA / PENGUMUMAN TERKINI
// =====================================

$queryBerita = "
SELECT *
FROM pengumuman
ORDER BY tarikh_cipta DESC, id DESC
LIMIT 5
";
// =====================================
// QUERY 6: TUGASAN AKAN DATANG
// =====================================

$queryUpcomingTask = "
SELECT
    t.id,
    t.tajuk,
    t.tarikh_akhir,
    k.nama_kelas
FROM tugasan t
INNER JOIN kelas k
    ON t.kelas_id = k.id
INNER JOIN kelas_pelajar kp
    ON k.id = kp.kelas_id
WHERE kp.pelajar_id = ?
AND t.status='aktif'
AND t.tarikh_akhir >= NOW()
ORDER BY t.tarikh_akhir ASC
LIMIT 5
";

$stmtTask = mysqli_prepare($conn,$queryUpcomingTask);

mysqli_stmt_bind_param($stmtTask,"i",$userId);

mysqli_stmt_execute($stmtTask);

$resultTask = mysqli_stmt_get_result($stmtTask);


$resultBerita = mysqli_query($conn,$queryBerita);

// =====================================
// QUERY 7: BAHAN TERBAHARU
// =====================================

$queryBahan = "
SELECT
    b.id,
    b.tajuk,
    b.fail,
    b.tarikh_cipta,
    k.nama_kelas
FROM bahan b
INNER JOIN kelas k
    ON b.kelas_id = k.id
INNER JOIN kelas_pelajar kp
    ON k.id = kp.kelas_id
WHERE kp.pelajar_id = ?
ORDER BY b.tarikh_cipta DESC
LIMIT 5
";

$stmtBahan = mysqli_prepare($conn, $queryBahan);
mysqli_stmt_bind_param($stmtBahan, "i", $userId);
mysqli_stmt_execute($stmtBahan);
$resultBahan = mysqli_stmt_get_result($stmtBahan);

// =====================================
// QUERY KALENDAR
// =====================================

// AKTIVITI
$queryCalendarAktiviti = "
    SELECT
        id,
        nama_aktiviti AS title,
        tarikh,
        masa,
        lokasi
    FROM aktiviti
    WHERE status = 'aktif'
    AND tarikh >= CURDATE()
    ORDER BY tarikh ASC
";

$resultCalendarAktiviti = mysqli_query($conn, $queryCalendarAktiviti);


// TUGASAN
$queryCalendarTugasan = "
    SELECT
        t.id,
        t.tajuk AS title,
        t.tarikh_akhir,
        k.nama_kelas
    FROM tugasan t

    INNER JOIN kelas k
        ON t.kelas_id = k.id

    INNER JOIN kelas_pelajar kp
        ON k.id = kp.kelas_id

    WHERE kp.pelajar_id = ?
    AND t.status = 'aktif'
    AND t.tarikh_akhir >= NOW()

    ORDER BY t.tarikh_akhir ASC
";

$stmtCalendarTugasan = mysqli_prepare(
    $conn,
    $queryCalendarTugasan
);

if (!$stmtCalendarTugasan) {
    die("Error Query Tugasan Calendar: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmtCalendarTugasan,
    "i",
    $userId
);

mysqli_stmt_execute($stmtCalendarTugasan);

$resultCalendarTugasan =
    mysqli_stmt_get_result($stmtCalendarTugasan);


// =====================================
// GABUNGKAN EVENT
// =====================================

$events = [];


// -----------------------------
// AKTIVITI
// -----------------------------

if ($resultCalendarAktiviti) {

    while ($row = mysqli_fetch_assoc($resultCalendarAktiviti)) {

        $startDate = $row['tarikh'];

        // Jika masa ada, gabungkan tarikh + masa
        if (!empty($row['masa'])) {
            $startDate .= 'T' . $row['masa'];
        }

        $events[] = [

            "title" =>
                "Aktiviti: " .
                $row['title'],

            "start" =>
                $startDate,

            "jenis" =>
                "aktiviti",

            "lokasi" =>
                $row['lokasi']

        ];
    }
}


// -----------------------------
// TUGASAN
// -----------------------------

if ($resultCalendarTugasan) {

    while ($row = mysqli_fetch_assoc(
        $resultCalendarTugasan
    )) {

        $events[] = [

            "title" =>
                "Tugasan: " .
                $row['title'],

            "start" =>
                $row['tarikh_akhir'],

            "jenis" =>
                "tugasan",

            "lokasi" =>
                "",

            "kelas" =>
                $row['nama_kelas']

        ];
    }
}


$calendarData = json_encode(
    $events,
    JSON_UNESCAPED_UNICODE
);
// Header
include("../../components/header.php");

// Sidebar
include("../../components/sidebar.php");
?>

<!-- MAIN CONTENT -->
<div class="main-content" id="mainContent">

    <!-- TOPBAR -->
    <?php include("../../components/topbar.php"); ?>

    <!-- PAGE HEADER -->
    <div class="page-header">

        <div class="greeting-section">

            <p class="greeting-text">Selamat Datang,</p>

            <h1 class="user-name">
                <?= htmlspecialchars($user['nama']); ?>
                <span class="wave-emoji">👋</span>
            </h1>

            <p class="institution-name">
                Kolej Vokasional Kuala Selangor
            </p>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="dashboard-content">

        <div class="stats-grid">

            <!-- Kelas -->
            <div class="stat-card blue">

                <div class="stat-icon">
                    <i class="fa-solid fa-book-open"></i>
                </div>

                <div class="stat-info">

                    <h2 class="stat-number"><?= $kelasAktif ?></h2>

                    <p class="stat-label">Kelas Aktif</p>

                    <a href="<?= $baseUrl ?>pages/pelajar/kelas.php" class="stat-link">
                        Lihat kelas
                    </a>

                </div>

            </div>

            <!-- Tugasan -->
            <div class="stat-card green">

                <div class="stat-icon">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>

                <div class="stat-info">

                    <h2 class="stat-number"><?= $tugasanAktif ?></h2>

                    <p class="stat-label">Tugasan Aktif</p>

                    <a href="<?= $baseUrl ?>pages/pelajar/tugasan.php" class="stat-link">
                        Lihat tugasan
                    </a>

                </div>

            </div>

            <!-- Pengumuman -->
            <div class="stat-card purple">

                <div class="stat-icon">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>

                <div class="stat-info">

                    <h2 class="stat-number"><?= $pengumumanBaharu ?></h2>

                    <p class="stat-label">Pengumuman Baharu</p>

                    <a href="<?= $baseUrl ?>pages/pelajar/pengumuman.php" class="stat-link">
                        Lihat semua
                    </a>

                </div>

            </div>

            <!-- Aktiviti -->
            <div class="stat-card orange">

                <div class="stat-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>

                <div class="stat-info">

                    <h2 class="stat-number"><?= $aktivitiAkanDatang ?></h2>

                    <p class="stat-label">Aktiviti Akan Datang</p>

                    <a href="<?= $baseUrl ?>pages/pelajar/aktiviti.php" class="stat-link">
                        Lihat aktiviti
                    </a>

                </div>

            </div>

        </div>

        <!-- ==========================
     BERITA TERKINI
========================== -->

<!-- =========================
     BERITA + KALENDAR
========================= -->

<div class="dashboard-row">


    <!-- KIRI : BERITA -->
    <div class="news-card">

        <div class="card-header">
            <h3>Pengumuman Terkini</h3>

            <a href="<?= $baseUrl ?>pages/pelajar/pengumuman.php">
                Lihat Semua
            </a>

        </div>


        <?php if(mysqli_num_rows($resultBerita)>0): ?>

        <?php while($berita=mysqli_fetch_assoc($resultBerita)): ?>

        <div class="news-item">

            <h4>
                <?= htmlspecialchars($berita['tajuk']) ?>
            </h4>


            <p>
                <?= htmlspecialchars(substr($berita['kandungan'],0,120)) ?>...
            </p>


            <small>
                <?= date("d M Y",strtotime($berita['tarikh_cipta'])) ?>
            </small>


        </div>


        <?php endwhile; ?>

        <?php else: ?>

        <p>Tiada Pengumuman.</p>

        <?php endif; ?>


    </div>



    <!-- KANAN : KALENDAR -->

    <div class="calendar-card">

        <div class="card-header">
            <h3>Kalendar</h3>
        </div>


        <div id="calendar"></div>


    </div>


</div>



<!-- =========================
     BAHAGIAN BAWAH
========================= -->

<div class="bottom-section">


    <!-- KIRI -->
    <div class="left-column">


        <!-- AKSES PANTAS -->
        <div class="quick-card">


            <div class="card-header">

                <h3>Akses Pantas</h3>

            </div>


            <div class="quick-grid">


                <a href="#" class="quick-btn">

                    <i class="fa-solid fa-book"></i>

                    <span>Kelas</span>

                </a>


                <a href="#" class="quick-btn">

                    <i class="fa-solid fa-file"></i>

                    <span>Tugasan</span>

                </a>


                <a href="#" class="quick-btn">

                    <i class="fa-solid fa-bullhorn"></i>

                    <span>Pengumuman</span>

                </a>


                <a href="#" class="quick-btn">

                    <i class="fa-solid fa-calendar"></i>

                    <span>Aktiviti</span>

                </a>


            </div>


        </div>




        <!-- BAHAN TERBAHARU -->

        <div class="material-card">


            <div class="card-header">

                <h3>Bahan Terbaharu</h3>


                <a href="#">
                    Lihat Semua
                </a>


            </div>


            <?php while($bahan=mysqli_fetch_assoc($resultBahan)): ?>


            <div class="material-item">


                <div class="material-icon">

                    <i class="fa-solid fa-file"></i>

                </div>



                <div class="material-info">


                    <h4>
                    <?= htmlspecialchars($bahan['tajuk']) ?>
                    </h4>


                    <p>
                    <?= htmlspecialchars($bahan['nama_kelas']) ?>
                    </p>


                    <small>
                    <?= date("d M Y",strtotime($bahan['tarikh_cipta'])) ?>
                    </small>


                </div>


            </div>


            <?php endwhile; ?>


        </div>


    </div>





    <!-- KANAN -->

    <div class="task-card">


        <div class="card-header">

            <h3>Tugasan Akan Datang</h3>


            <a href="#">
                Lihat Semua
            </a>


        </div>



        <?php if(mysqli_num_rows($resultTask)>0): ?>


        <?php while($task=mysqli_fetch_assoc($resultTask)): ?>


        <div class="task-item">


            <div>

                <h4>
                <?= htmlspecialchars($task['tajuk']) ?>
                </h4>


                <small>
                <?= htmlspecialchars($task['nama_kelas']) ?>
                </small>


            </div>



            <span>

            <?= date("d M",strtotime($task['tarikh_akhir'])) ?>

            </span>



        </div>


        <?php endwhile; ?>


        <?php else: ?>


        <p>Tiada tugasan.</p>


        <?php endif; ?>


    </div>


</div>

</div>

    </div>

</div>

<div class="sidebar-overlay"></div>

</body>
</html>