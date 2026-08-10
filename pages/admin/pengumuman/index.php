<?php

session_start();

/* =================================================
   SEMAK LOGIN ADMIN
================================================= */

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {

    header("Location: ../../login.php");
    exit();

}


/* =================================================
   DATABASE
================================================= */

include("../../../components/config.php");

/*=============================================
   AMBIL DATA PENGGUNA
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
   AMBIL DATA PENGUMUMAN
================================================= */

$sql = "
    SELECT
        id,
        tajuk,
        kandungan,
        sasaran,
        status,
        tarikh_cipta
    FROM pengumuman
    ORDER BY tarikh_cipta DESC
";

$result = mysqli_query($conn, $sql);


/* =================================================
   PAGE CSS
================================================= */

$pageCss = "pengumuman.css";

include(
    "../../../asset/admin/admin-components/adminHead.php"
);

?>


<body>

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
     MAIN
================================================= -->

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

                <i class="fa-solid fa-bullhorn"></i>

                Pengumuman

            </span>


            <h1>
                Pengumuman
            </h1>


            <p>
                Urus pengumuman yang dipaparkan kepada
                pengguna IR-KVKS.
            </p>

        </div>


        <!-- TAMBAH -->

        <a
            href="tambah.php"
            class="add-btn"
        >

            <i class="fa-solid fa-plus"></i>

            Tambah Pengumuman

        </a>

    </div>


    <!-- =================================================
         CONTENT
    ================================================== -->

    <div class="announcement-content">


        <!-- =================================================
             SEARCH
        ================================================== -->

        <div class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                id="searchAnnouncement"
                placeholder="Cari pengumuman..."
            >

        </div>


        <!-- =================================================
             ANNOUNCEMENT CARD
        ================================================== -->

        <div class="announcement-card">


            <!-- HEADER -->

            <div class="table-header">

                <div>

                    <h2>
                        Senarai Pengumuman
                    </h2>

                    <p>
                        Semua pengumuman dalam sistem.
                    </p>

                </div>


                <?php

                if ($result) {

                    $jumlah = mysqli_num_rows($result);

                } else {

                    $jumlah = 0;

                }

                ?>

                <span class="page-badge">

                    <?= $jumlah ?> Pengumuman

                </span>

            </div>


            <!-- =================================================
                 TABLE CONTAINER
            ================================================== -->

            <div class="table-container">


                <table id="announcementTable">


                    <!-- =================================================
                         TABLE HEAD
                    ================================================== -->

                    <thead>

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Tajuk
                            </th>

                            <th>
                                Kandungan
                            </th>

                            <th>
                                Sasaran
                            </th>

                            <th>
                                Tarikh
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Tindakan
                            </th>

                        </tr>

                    </thead>


                    <!-- =================================================
                         TABLE BODY
                    ================================================== -->

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


                            <!-- =================================================
                                 BIL
                            ================================================== -->

                            <td>

                                <?= $no++ ?>

                            </td>


                            <!-- =================================================
                                 TAJUK
                            ================================================== -->

                            <td>

                                <div class="announcement-title">


                                    <div class="announcement-icon">

                                        <i class="fa-solid fa-bullhorn"></i>

                                    </div>


                                    <strong>

                                        <?= htmlspecialchars(
                                            $row['tajuk']
                                        ) ?>

                                    </strong>


                                </div>

                            </td>


                            <!-- =================================================
                                 KANDUNGAN
                            ================================================== -->

                            <td>

                                <span class="description">

                                    <?= htmlspecialchars(
                                        mb_strimwidth(
                                            $row['kandungan'],
                                            0,
                                            80,
                                            "..."
                                        )
                                    ) ?>

                                </span>

                            </td>


                            <!-- =================================================
                                 SASARAN
                            ================================================== -->

                            <td>

                                <?php

                                $sasaran =
                                    $row['sasaran'];

                                ?>


                                <?php if (
                                    $sasaran === 'semua'
                                ): ?>


                                    <span class="status active">

                                        <i class="fa-solid fa-users"></i>

                                        Semua

                                    </span>


                                <?php elseif (
                                    $sasaran === 'pelajar'
                                ): ?>


                                    <span class="status active">

                                        <i class="fa-solid fa-user-graduate"></i>

                                        Pelajar

                                    </span>


                                <?php elseif (
                                    $sasaran === 'pensyarah'
                                ): ?>


                                    <span class="status active">

                                        <i class="fa-solid fa-chalkboard-user"></i>

                                        Pensyarah

                                    </span>


                                <?php endif; ?>


                            </td>


                            <!-- =================================================
                                 TARIKH
                            ================================================== -->

                            <td>

                                <span class="date">

                                    <i class="fa-regular fa-calendar"></i>


                                    <?php

                                    if (
                                        !empty(
                                            $row['tarikh_cipta']
                                        )
                                    ) {

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $row['tarikh_cipta']
                                            )
                                        );

                                    } else {

                                        echo "-";

                                    }

                                    ?>

                                </span>

                            </td>


                            <!-- =================================================
                                 STATUS
                            ================================================== -->

                            <td>


                                <?php if (
                                    $row['status'] === 'aktif'
                                ): ?>


                                    <span class="status active">

                                        <i class="fa-solid fa-circle-check"></i>

                                        Aktif

                                    </span>


                                <?php else: ?>


                                    <span class="status inactive">

                                        <i class="fa-solid fa-circle-xmark"></i>

                                        Tidak Aktif

                                    </span>


                                <?php endif; ?>


                            </td>


                            <!-- =================================================
                                 TINDAKAN
                            ================================================== -->

                            <td>

                                <div class="action-buttons">


                                    <!-- EDIT -->

                                    <a
                                        href="edit.php?id=<?= $row['id'] ?>"
                                        class="action-btn edit"
                                        title="Edit"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                    </a>


                                    <!-- PADAM -->

                                    <a
                                        href="padam.php?id=<?= $row['id'] ?>"
                                        class="action-btn delete"
                                        title="Padam"
                                        onclick="return confirm('Adakah anda pasti mahu memadam pengumuman ini?');"
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
                                colspan="7"
                                class="empty-table"
                            >


                                <div class="empty-icon">

                                    <i class="fa-solid fa-bullhorn"></i>

                                </div>


                                <h3>
                                    Tiada Pengumuman
                                </h3>


                                <p>
                                    Belum terdapat pengumuman
                                    dalam sistem.
                                </p>


                                <a
                                    href="tambah.php"
                                    class="add-btn"
                                >

                                    <i class="fa-solid fa-plus"></i>

                                    Tambah Pengumuman

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
================================================= -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        const search =
            document.getElementById(
                "searchAnnouncement"
            );


        const table =
            document.getElementById(
                "announcementTable"
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


                        if (
                            text.includes(
                                keyword
                            )
                        ) {

                            row.style.display =
                                "";

                        } else {

                            row.style.display =
                                "none";

                        }


                    }
                );


            }
        );


    }
);

</script>


</body>

</html>