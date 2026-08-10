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
// CSS KHAS PAGE
// =====================================
$pageCss = "aktiviti.css";

// =====================================
// DATABASE
// =====================================
include(__DIR__ . "/../../components/config.php");

// =====================================
// DAPATKAN MAKLUMAT PELAJAR
// =====================================
$userId = $_SESSION['user_id'];

$sqlUser = "
    SELECT id, nama, email, role, gambar
    FROM users
    WHERE id = ?
    LIMIT 1
";

$stmtUser = mysqli_prepare($conn, $sqlUser);

if (!$stmtUser) {
    die("Ralat query pengguna: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmtUser, "i", $userId);
mysqli_stmt_execute($stmtUser);

$resultUser = mysqli_stmt_get_result($stmtUser);

$user = mysqli_fetch_assoc($resultUser);

mysqli_stmt_close($stmtUser);


// =====================================
// DAPATKAN SENARAI AKTIVITI
// =====================================

$query = "
    SELECT
        id,
        nama_aktiviti,
        keterangan,
        tarikh,
        masa,
        lokasi,
        status
    FROM aktiviti
    WHERE status = 'aktif'
    ORDER BY tarikh ASC, masa ASC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Ralat query aktiviti: " . mysqli_error($conn));
}


// =====================================
// HEADER
// =====================================
$pageCss = "aktiviti.css";
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

                <i class="fa-solid fa-calendar-days"></i>

                Aktiviti

            </span>


            <h1>
                Aktiviti Pelajar
            </h1>


            <p>
                Lihat aktiviti dan program yang akan berlangsung.
            </p>

        </div>

    </div>


    <!-- =====================================
         CONTENT
    ====================================== -->

    <div class="dashboard-content">


        <!-- SEARCH -->

        <div class="activity-search">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                id="searchActivity"
                placeholder="Cari aktiviti..."
                autocomplete="off"
            >

        </div>


        <!-- =====================================
             ACTIVITY GRID
        ====================================== -->

        <div
            class="activity-grid"
            id="activityGrid"
        >


            <?php if (mysqli_num_rows($result) > 0): ?>


                <?php while ($aktiviti = mysqli_fetch_assoc($result)): ?>


                    <div
                        class="activity-card"
                        data-search="<?= htmlspecialchars(
                            strtolower(
                                $aktiviti['nama_aktiviti']
                                . ' '
                                . $aktiviti['keterangan']
                                . ' '
                                . $aktiviti['lokasi']
                            )
                        ) ?>"
                    >


                        <!-- =================================
                             TOP
                        ================================== -->

                        <div class="activity-top">


                            <div class="activity-icon">

                                <i class="fa-solid fa-calendar-check"></i>

                            </div>


                            <span class="activity-status">

                                Aktif

                            </span>


                        </div>


                        <!-- =================================
                             TITLE
                        ================================== -->

                        <h3 class="activity-title">

                            <?= htmlspecialchars(
                                $aktiviti['nama_aktiviti']
                            ) ?>

                        </h3>


                        <!-- =================================
                             DESCRIPTION
                        ================================== -->

                        <p class="activity-description">

                            <?= !empty($aktiviti['keterangan'])
                                ? htmlspecialchars(
                                    $aktiviti['keterangan']
                                )
                                : "Tiada penerangan untuk aktiviti ini."
                            ?>

                        </p>


                        <!-- =================================
                             INFO
                        ================================== -->

                        <div class="activity-info">


                            <!-- TARIKH -->

                            <div class="activity-info-item">

                                <div class="info-icon">

                                    <i class="fa-solid fa-calendar"></i>

                                </div>


                                <div>

                                    <span>
                                        Tarikh
                                    </span>


                                    <strong>

                                        <?= !empty($aktiviti['tarikh'])
                                            ? date(
                                                "d M Y",
                                                strtotime(
                                                    $aktiviti['tarikh']
                                                )
                                            )
                                            : "Tidak ditetapkan"
                                        ?>

                                    </strong>

                                </div>

                            </div>


                            <!-- MASA -->

                            <div class="activity-info-item">

                                <div class="info-icon">

                                    <i class="fa-solid fa-clock"></i>

                                </div>


                                <div>

                                    <span>
                                        Masa
                                    </span>


                                    <strong>

                                        <?php

                                        if (!empty($aktiviti['masa'])) {

                                            echo date(
                                                "h:i A",
                                                strtotime(
                                                    $aktiviti['masa']
                                                )
                                            );

                                        } else {

                                            echo "Tidak ditetapkan";

                                        }

                                        ?>

                                    </strong>

                                </div>

                            </div>


                            <!-- LOKASI -->

                            <div class="activity-info-item">

                                <div class="info-icon">

                                    <i class="fa-solid fa-location-dot"></i>

                                </div>


                                <div>

                                    <span>
                                        Lokasi
                                    </span>


                                    <strong>

                                        <?= !empty($aktiviti['lokasi'])
                                            ? htmlspecialchars(
                                                $aktiviti['lokasi']
                                            )
                                            : "Tidak ditetapkan"
                                        ?>

                                    </strong>

                                </div>

                            </div>


                        </div>


                    </div>


                <?php endwhile; ?>


                <!-- SEARCH EMPTY -->

                <div
                    class="activity-search-empty"
                    id="searchEmpty"
                    style="display:none;"
                >

                    <div class="empty-icon">

                        <i class="fa-solid fa-magnifying-glass"></i>

                    </div>


                    <h2>
                        Aktiviti tidak dijumpai
                    </h2>


                    <p>
                        Cuba gunakan kata kunci yang lain.
                    </p>

                </div>


            <?php else: ?>


                <!-- =================================
                     TIADA AKTIVITI
                ================================== -->

                <div class="activity-empty">

                    <div class="empty-icon">

                        <i class="fa-solid fa-calendar-xmark"></i>

                    </div>


                    <h2>
                        Tiada aktiviti
                    </h2>


                    <p>
                        Buat masa ini tiada aktiviti yang tersedia.
                    </p>

                </div>


            <?php endif; ?>


        </div>


    </div>


</div>


<div class="sidebar-overlay"></div>


<!-- =====================================
     SEARCH JAVASCRIPT
====================================== -->

<script>

const searchActivity =
    document.getElementById("searchActivity");

const activityCards =
    document.querySelectorAll(".activity-card");

const searchEmpty =
    document.getElementById("searchEmpty");


if (searchActivity) {

    searchActivity.addEventListener("input", function () {

        const keyword =
            this.value.toLowerCase().trim();

        let found = false;


        activityCards.forEach(function (card) {

            const text =
                card.dataset.search.toLowerCase();

            if (text.includes(keyword)) {

                card.style.display = "";

                found = true;

            } else {

                card.style.display = "none";

            }

        });


        if (searchEmpty) {

            searchEmpty.style.display =
                (!found && keyword !== "")
                ? "block"
                : "none";

        }

    });

}

</script>


</body>
</html>