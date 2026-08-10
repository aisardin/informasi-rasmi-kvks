<?php

session_start();

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: ../../../login.php");
    exit();
}

include("../../../components/config.php");

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

/* ==========================================
   GET AKTIVITI
========================================== */

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM aktiviti
     ORDER BY tarikh DESC, masa DESC"
);

if (!$result) {
    die("Ralat database: " . mysqli_error($conn));
}

?>

<?php
$pageTitle = "Aktiviti";    
$pageCss = "admin-aktiviti.css";
include("../../../asset/admin/admin-components/adminHead.php");
?>
<!-- =================================================
     TOPBAR
================================================== -->

<?php

include(
    "../../../asset/admin/admin-components/admin-topbar.php"
);

?>
<!-- =================================================
     SIDEBAR OVERLAY
================================================= -->

<div class="admin-sidebar-overlay"></div>


<!-- =================================================
     SIDEBAR
================================================= -->

<?php

include(
    "../../../asset/admin/admin-components/admin-sidebar.php"
);

?>

<!-- =================================================
     PAGE HEADER
================================================== -->

<div class="page-header">

    <div>

        <span class="page-badge">

            <i class="fa-solid fa-calendar-days"></i>

            Aktiviti

        </span>


        <h1>
            Aktiviti
        </h1>


        <p>
            Urus aktiviti yang dijalankan oleh KVKS.
        </p>

    </div>


    <!-- TAMBAH -->

    <a
        href="tambah.php"
        class="add-btn"
    >

        <i class="fa-solid fa-plus"></i>

        Tambah Aktiviti

    </a>

</div>



<!-- =================================================
     CONTENT
================================================== -->

<div class="activity-content">


    <!-- =================================================
         SEARCH
    ================================================== -->

    <div class="search-box">

        <i class="fa-solid fa-magnifying-glass"></i>

        <input
            type="text"
            id="searchActivity"
            placeholder="Cari aktiviti..."
        >

    </div>



    <!-- =================================================
         CARD
    ================================================== -->

    <div class="activity-card">


        <!-- CARD HEADER -->

        <div class="table-header">

            <div>

                <h2>
                    Senarai Aktiviti
                </h2>

                <p>
                    Semua aktiviti yang terdapat dalam sistem.
                </p>

            </div>


            <?php

            $jumlahAktiviti = mysqli_num_rows($result);

            ?>

            <span class="total-badge">

                <?= $jumlahAktiviti ?> Aktiviti

            </span>

        </div>



        <!-- =================================================
             TABLE
        ================================================== -->

        <div class="table-container">

            <table id="activityTable">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Aktiviti</th>

                        <th>Tarikh & Masa</th>

                        <th>Lokasi</th>

                        <th>Status</th>

                        <th>Tindakan</th>

                    </tr>

                </thead>


                <tbody>


                <?php if (
                    $result &&
                    mysqli_num_rows($result) > 0
                ): ?>


                    <?php

                    $no = 1;

                    while (
                        $row =
                        mysqli_fetch_assoc($result)
                    ):

                    ?>


                    <tr>


                        <!-- =================================
                             BIL
                        ================================== -->

                        <td>

                            <span class="row-number">

                                <?= $no++ ?>

                            </span>

                        </td>



                        <!-- =================================
                             AKTIVITI
                        ================================== -->

                        <td>

                            <div class="activity-title">


                                <div class="activity-icon">

                                    <i class="fa-solid fa-calendar-days"></i>

                                </div>


                                <div class="activity-info">

                                    <strong>

                                        <?= htmlspecialchars(
                                            $row['nama_aktiviti']
                                            ?? 'Aktiviti'
                                        ) ?>

                                    </strong>


                                    <small>

                                        <?= htmlspecialchars(
                                            mb_strimwidth(
                                                $row['keterangan']
                                                ?? '',
                                                0,
                                                70,
                                                "..."
                                            )
                                        ) ?>

                                    </small>

                                </div>


                            </div>

                        </td>



                        <!-- =================================
                             TARIKH & MASA
                        ================================== -->

                        <td>

                            <div class="activity-datetime">


                                <span class="activity-date">

                                    <i class="fa-regular fa-calendar"></i>

                                    <?php

                                    if (!empty($row['tarikh'])) {

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $row['tarikh']
                                            )
                                        );

                                    } else {

                                        echo "Belum ditetapkan";

                                    }

                                    ?>

                                </span>


                                <?php if (!empty($row['masa'])): ?>

                                    <span class="activity-time">

                                        <i class="fa-regular fa-clock"></i>

                                        <?= date(
                                            "h:i A",
                                            strtotime(
                                                $row['masa']
                                            )
                                        ) ?>

                                    </span>

                                <?php endif; ?>


                            </div>

                        </td>



                        <!-- =================================
                             LOKASI
                        ================================== -->

                        <td>

                            <span class="activity-location">

                                <i class="fa-solid fa-location-dot"></i>


                                <?= htmlspecialchars(
                                    $row['lokasi']
                                    ?? '-'
                                ) ?>


                            </span>

                        </td>



                        <!-- =================================
                             STATUS
                        ================================== -->

                        <td>


                            <?php

                            $status =
                                strtolower(
                                    trim(
                                        $row['status']
                                        ?? ''
                                    )
                                );

                            ?>


                            <?php if (
                                $status === 'aktif'
                            ): ?>


                                <span class="status active">

                                    <span class="status-dot"></span>

                                    Aktif

                                </span>


                            <?php elseif (
                                $status === 'tamat'
                            ): ?>


                                <span class="status completed">

                                    <span class="status-dot"></span>

                                    Tamat

                                </span>


                            <?php else: ?>


                                <span class="status inactive">

                                    Tidak Diketahui

                                </span>


                            <?php endif; ?>


                        </td>



                        <!-- =================================
                             TINDAKAN
                        ================================== -->

                        <td>

                            <div class="action-buttons">


                                <!-- EDIT -->

                                <a
                                    href="edit.php?id=<?= $row['id'] ?>"
                                    class="action-btn edit"
                                    title="Edit Aktiviti"
                                >

                                    <i class="fa-solid fa-pen"></i>

                                </a>



                                <!-- PADAM -->

                                <a
                                    href="padam.php?id=<?= $row['id'] ?>"
                                    class="action-btn delete"
                                    title="Padam Aktiviti"

                                    onclick="return confirm(
                                        'Adakah anda pasti mahu memadam aktiviti ini?'
                                    );"
                                >

                                    <i class="fa-solid fa-trash"></i>

                                </a>


                            </div>

                        </td>


                    </tr>


                    <?php endwhile; ?>


                <?php else: ?>


                    <!-- =================================
                         EMPTY
                    ================================== -->

                    <tr>

                        <td
                            colspan="6"
                            class="empty-table"
                        >


                            <div class="empty-icon">

                                <i class="fa-solid fa-calendar-xmark"></i>

                            </div>


                            <h3>
                                Tiada Aktiviti
                            </h3>


                            <p>
                                Belum terdapat aktiviti dalam sistem.
                            </p>


                            <a
                                href="tambah.php"
                                class="add-btn"
                            >

                                <i class="fa-solid fa-plus"></i>

                                Tambah Aktiviti

                            </a>


                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>

            </table>

        </div>

    </div>

</div>



<!-- =================================================
     SEARCH JAVASCRIPT
================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const search =
            document.getElementById(
                "searchActivity"
            );

        const table =
            document.getElementById(
                "activityTable"
            );


        if (!search || !table) {
            return;
        }


        search.addEventListener(
            "input",
            function () {

                const keyword =
                    this.value
                        .toLowerCase()
                        .trim();


                const rows =
                    table.querySelectorAll(
                        "tbody tr"
                    );


                rows.forEach(
                    function (row) {

                        const text =
                            row.textContent
                                .toLowerCase();


                        row.style.display =
                            text.includes(keyword)
                                ? ""
                                : "none";

                    }
                );

            }
        );

    }
);

</script>