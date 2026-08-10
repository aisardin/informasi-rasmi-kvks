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


// =================================================
// AMBIL DATA AKADEMIK
// =================================================

$sql = "
    SELECT 
        akademik.*,
        users.nama AS nama_pensyarah
    FROM akademik
    LEFT JOIN users 
        ON akademik.pensyarah_id = users.id
    ORDER BY akademik.id DESC
";

$result = mysqli_query($conn, $sql);


// =================================================
// CSS
// =================================================

$pageCss = "admin-akademik.css";

include(
    "../../../asset/admin/admin-components/adminHead.php"
);
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
?>

<body>

<!-- =================================================
     SIDEBAR OVERLAY
================================================== -->

<div class="admin-sidebar-overlay"></div>


<!-- =================================================
     SIDEBAR
================================================== -->

<?php

include(
    "../../../asset/admin/admin-components/admin-sidebar.php"
);

?>


<!-- =================================================
     MAIN
================================================== -->

<div class="admin-main">


    <!-- =================================================
         TOPBAR
    ================================================== -->

    <?php

    include(
        "../../../asset/admin/admin-components/admin-topbar.php"
    );

    ?>


    <!-- =================================================
         PAGE HEADER
    ================================================== -->

    <div class="page-header">

        <div>

            <span class="page-badge">

                <i class="fa-solid fa-book-open"></i>

                Akademik

            </span>


            <h1>
                Akademik
            </h1>


            <p>
                Urus maklumat kelas dan akademik
                dalam sistem IR-KVKS.
            </p>

        </div>


        <!-- TAMBAH -->

        <a
            href="tambah.php"
            class="add-btn"
        >

            <i class="fa-solid fa-plus"></i>

            Tambah Akademik

        </a>

    </div>



    <!-- =================================================
         CONTENT
    ================================================== -->

    <div class="academic-content">


        <!-- =================================================
             SEARCH
        ================================================== -->

        <div class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                id="searchAcademic"
                placeholder="Cari kod subjek, kelas, program..."
            >

        </div>



        <!-- =================================================
             CARD
        ================================================== -->

        <div class="academic-card">


            <!-- CARD HEADER -->

            <div class="table-header">

                <div>

                    <h2>
                        Senarai Akademik
                    </h2>

                    <p>
                        Semua kelas dan maklumat akademik
                        dalam sistem.
                    </p>

                </div>


                <?php

                $jumlahAkademik = 0;

                if ($result) {

                    $jumlahAkademik =
                        mysqli_num_rows($result);

                }

                ?>


                <span class="total-badge">

                    <?= $jumlahAkademik ?>

                    Akademik

                </span>

            </div>



            <!-- =================================================
                 TABLE
            ================================================== -->

            <div class="table-container">

                <table id="academicTable">


                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Subjek</th>

                            <th>Kelas</th>

                            <th>Program</th>

                            <th>Pensyarah</th>

                            <th>Jadual</th>

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
                                 SUBJEK
                            ================================== -->

                            <td>

                                <div class="subject-info">

                                    <div class="subject-icon">

                                        <i class="fa-solid fa-book"></i>

                                    </div>


                                    <div>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $row['kod_subjek']
                                            ) ?>

                                        </strong>


                                        <small>

                                            Semester
                                            <?= htmlspecialchars(
                                                $row['semester']
                                                ?? '-'
                                            ) ?>

                                        </small>

                                    </div>

                                </div>

                            </td>



                            <!-- =================================
                                 KELAS
                            ================================== -->

                            <td>

                                <div class="class-info">

                                    <strong>

                                        <?= htmlspecialchars(
                                            $row['nama_kelas']
                                        ) ?>

                                    </strong>


                                    <small>

                                        Sesi:
                                        <?= htmlspecialchars(
                                            $row['sesi']
                                            ?? '-'
                                        ) ?>

                                    </small>

                                </div>

                            </td>



                            <!-- =================================
                                 PROGRAM
                            ================================== -->

                            <td>

                                <span class="program-badge">

                                    <?= htmlspecialchars(
                                        $row['program']
                                        ?? '-'
                                    ) ?>

                                </span>

                            </td>



                            <!-- =================================
                                 PENSYARAH
                            ================================== -->

                            <td>

                                <div class="lecturer-info">

                                    <div class="lecturer-icon">

                                        <i class="fa-solid fa-chalkboard-user"></i>

                                    </div>


                                    <span>

                                        <?= htmlspecialchars(
                                            $row['nama_pensyarah']
                                            ?? 'Tidak ditetapkan'
                                        ) ?>

                                    </span>

                                </div>

                            </td>



                            <!-- =================================
                                 JADUAL
                            ================================== -->

                            <td>

                                <div class="schedule-info">


                                    <?php if (
                                        !empty($row['hari'])
                                    ): ?>

                                        <span class="schedule-day">

                                            <i class="fa-regular fa-calendar"></i>

                                            <?= htmlspecialchars(
                                                $row['hari']
                                            ) ?>

                                        </span>

                                    <?php endif; ?>


                                    <?php if (
                                        !empty($row['masa_mula']) ||
                                        !empty($row['masa_tamat'])
                                    ): ?>

                                        <span class="schedule-time">

                                            <i class="fa-regular fa-clock"></i>

                                            <?php

                                            if (
                                                !empty(
                                                    $row['masa_mula']
                                                )
                                            ) {

                                                echo date(
                                                    "h:i A",
                                                    strtotime(
                                                        $row['masa_mula']
                                                    )
                                                );

                                            }

                                            echo " - ";


                                            if (
                                                !empty(
                                                    $row['masa_tamat']
                                                )
                                            ) {

                                                echo date(
                                                    "h:i A",
                                                    strtotime(
                                                        $row['masa_tamat']
                                                    )
                                                );

                                            }

                                            ?>

                                        </span>

                                    <?php else: ?>

                                        <span>
                                            Tiada jadual
                                        </span>

                                    <?php endif; ?>


                                </div>

                            </td>



                            <!-- =================================
                                 LOKASI
                            ================================== -->

                            <td>

                                <span class="location-info">

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


                                <?php else: ?>


                                    <span class="status inactive">

                                        <span class="status-dot"></span>

                                        Tidak Aktif

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
                                        title="Edit Akademik"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                    </a>



                                    <!-- PADAM -->

                                    <a
                                        href="padam.php?id=<?= $row['id'] ?>"
                                        class="action-btn delete"
                                        title="Padam Akademik"

                                        onclick="return confirm(
                                            'Adakah anda pasti mahu memadam rekod akademik ini?'
                                        );"
                                    >

                                        <i class="fa-solid fa-trash"></i>

                                    </a>


                                </div>

                            </td>


                        </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <!-- =================================================
                             EMPTY
                        ================================================== -->

                        <tr>

                            <td
                                colspan="9"
                                class="empty-table"
                            >


                                <div class="empty-icon">

                                    <i class="fa-solid fa-book-open"></i>

                                </div>


                                <h3>
                                    Tiada Data Akademik
                                </h3>


                                <p>
                                    Belum terdapat maklumat akademik
                                    dalam sistem.
                                </p>


                                <a
                                    href="tambah.php"
                                    class="add-btn"
                                >

                                    <i class="fa-solid fa-plus"></i>

                                    Tambah Akademik

                                </a>


                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>

                </table>

            </div>

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
                "searchAcademic"
            );

        const table =
            document.getElementById(
                "academicTable"
            );


        if (!search || !table) {
            return;
        }


        search.addEventListener(
            "input",
            function () {

                const keyword =
                    this.value.toLowerCase();


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


</body>
</html>