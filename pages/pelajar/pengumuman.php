<?php
session_start();

// Semak Login
if (!isset($_SESSION['role']) || $_SESSION['role'] != "pelajar") {
    header("Location: ../../pages/login.php");
    exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$baseUrl = "../../";

// Database
include(__DIR__ . "/../../components/config.php");

// Maklumat pengguna
$userId = $_SESSION['user_id'];

$sqlUser = "SELECT * FROM users WHERE id = ? LIMIT 1";

$stmtUser = mysqli_prepare($conn, $sqlUser);
mysqli_stmt_bind_param($stmtUser, "i", $userId);
mysqli_stmt_execute($stmtUser);

$resultUser = mysqli_stmt_get_result($stmtUser);
$user = mysqli_fetch_assoc($resultUser);

mysqli_stmt_close($stmtUser);

// =============================
// DAPATKAN SEMUA PENGUMUMAN
// =============================

$queryPengumuman = "
SELECT *
FROM pengumuman
WHERE status='aktif'
AND (sasaran='pelajar' OR sasaran='semua')
ORDER BY tarikh_cipta DESC
";

$resultPengumuman = mysqli_query($conn, $queryPengumuman);

$pageCss = "pengumuman.css";
include("../../components/header.php");

// Sidebar
include("../../components/sidebar.php");
?>

<body>

<div class="main-content" id="mainContent">

    <?php include("../../components/topbar.php"); ?>

    <div class="page-header">

        <h1>Pengumuman</h1>

        <p>
            Semua pengumuman rasmi Kolej Vokasional Kuala Selangor.
        </p>

    </div>

    <div class="dashboard-content">

        <?php if(mysqli_num_rows($resultPengumuman)>0): ?>

            <?php while($row=mysqli_fetch_assoc($resultPengumuman)): ?>

                <div class="announcement-card">
                    <div class="announcement-header">

                        <div class="announcement-title">

                            <div class="announcement-icon">
                                <i class="fa-solid fa-bullhorn"></i>
                            </div>

                            <div>

                                <h3><?= htmlspecialchars($row['tajuk']) ?></h3>

                                <span class="announcement-date">
                                    <?= date("d M Y", strtotime($row['tarikh_cipta'])) ?>
                                </span>

                            </div>

                        </div>

                    </div>

                    <p>
                        <?= nl2br(htmlspecialchars($row['kandungan'])) ?>
                    </p>

                    <div class="announcement-footer">

                        <span class="badge">
                            <?= ucfirst($row['sasaran']) ?>
                        </span>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="announcement-card">

                <p>Tiada pengumuman buat masa ini.</p>

            </div>

        <?php endif; ?>

    </div>

</div>

<div class="sidebar-overlay"></div>

</body>
</html>