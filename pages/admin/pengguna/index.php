
<?php

session_start();

/* =================================================
   SEMAK LOGIN ADMIN
================================================= */

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: ../../../login.php");
    exit();
}


/* =================================================
   DATABASE
================================================= */

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


/* =================================================
   AMBIL DATA PENGGUNA
================================================= */

$result = mysqli_query(
    $conn,
    "SELECT
        id,
        nama,
        email,
        role,
        gambar,
        telefon,
        status,
        tarikh_daftar
     FROM users
     ORDER BY id DESC"
);

?>

<!DOCTYPE html>

<html lang="ms">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pengguna | IR-KVKS</title>


    <!-- ADMIN HEAD -->

    <?php
    $pageCss = "admin-pengguna.css";
    include(
        "../../../asset/admin/admin-components/adminHead.php"
    );
    ?>


    <!-- PAGE CSS -->

    <link
        rel="stylesheet"
        href="../../../asset/admin/css/admin-pengguna.css"
    >

</head>


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

                <i class="fa-solid fa-users"></i>

                Pengguna

            </span>


            <h1>
                Pengguna
            </h1>


            <p>
                Urus akaun pengguna dalam sistem IR-KVKS.
            </p>

        </div>


        <!-- TAMBAH -->

        <a
            href="tambah.php"
            class="add-btn"
        >

            <i class="fa-solid fa-user-plus"></i>

            Tambah Pengguna

        </a>

    </div>


    <!-- =================================================
         CONTENT
    ================================================== -->

    <div class="user-content">


        <!-- =================================================
             SEARCH
        ================================================== -->

        <div class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>


            <input
                type="text"
                id="searchUser"
                placeholder="Cari nama, email atau role..."
            >

        </div>


        <!-- =================================================
             USER CARD
        ================================================== -->

        <div class="user-card">


            <!-- HEADER -->

            <div class="table-header">

                <div>

                    <h2>
                        Senarai Pengguna
                    </h2>

                    <p>
                        Semua akaun pengguna dalam sistem.
                    </p>

                </div>


                <?php

                $jumlahPengguna = 0;

                if ($result) {
                    $jumlahPengguna =
                        mysqli_num_rows($result);
                }

                ?>

                <span class="user-count">

                    <?= $jumlahPengguna ?>

                    Pengguna

                </span>

            </div>


            <!-- =================================================
                 TABLE
            ================================================== -->

            <div class="table-container">


                <table id="userTable">


                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Pengguna</th>

                            <th>Email</th>

                            <th>Telefon</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th>Tarikh Daftar</th>

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


                            <!-- BIL -->

                            <td>

                                <?= $no++ ?>

                            </td>


                            <!-- PENGGUNA -->

                            <td>

                                <div class="user-profile">


                                    <div class="user-avatar">


                                        <?php

                                        /*
                                         * Jika ada gambar
                                         */

                                        if (
                                            !empty(
                                                $row['gambar']
                                            )
                                        ):

                                        ?>

                                            <img
                                                src="../../../uploads/profile/<?= htmlspecialchars(
                                                    $row['gambar']
                                                ) ?>"
                                                alt="Profile"
                                            >

                                        <?php else: ?>

                                            <div class="default-avatar">

                                                <i class="fa-solid fa-user"></i>

                                            </div>

                                        <?php endif; ?>


                                    </div>


                                    <div class="user-info">

                                        <strong>

                                            <?= htmlspecialchars(
                                                $row['nama']
                                            ) ?>

                                        </strong>


                                        <span>

                                            ID:
                                            #<?= $row['id'] ?>

                                        </span>

                                    </div>


                                </div>

                            </td>


                            <!-- EMAIL -->

                            <td>

                                <span class="email">

                                    <i class="fa-regular fa-envelope"></i>

                                    <?= htmlspecialchars(
                                        $row['email']
                                    ) ?>

                                </span>

                            </td>


                            <!-- TELEFON -->

                            <td>

                                <?php if (
                                    !empty(
                                        $row['telefon']
                                    )
                                ): ?>

                                    <span class="phone">

                                        <i class="fa-solid fa-phone"></i>

                                        <?= htmlspecialchars(
                                            $row['telefon']
                                        ) ?>

                                    </span>

                                <?php else: ?>

                                    <span class="not-available">
                                        -
                                    </span>

                                <?php endif; ?>

                            </td>


                            <!-- ROLE -->

                            <td>

                                <?php

                                $roleClass =
                                    strtolower(
                                        $row['role']
                                    );

                                ?>

                                <?php if (
                                    $row['role']
                                    === 'admin'
                                ): ?>

                                    <span class="role admin">

                                        <i class="fa-solid fa-shield-halved"></i>

                                        Admin

                                    </span>

                                <?php elseif (
                                    $row['role']
                                    === 'pensyarah'
                                ): ?>

                                    <span class="role pensyarah">

                                        <i class="fa-solid fa-chalkboard-user"></i>

                                        Pensyarah

                                    </span>

                                <?php else: ?>

                                    <span class="role pelajar">

                                        <i class="fa-solid fa-user-graduate"></i>

                                        Pelajar

                                    </span>

                                <?php endif; ?>

                            </td>


                            <!-- STATUS -->

                            <td>

                                <?php if (
                                    $row['status']
                                    === 'aktif'
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


                            <!-- TARIKH -->

                            <td>

                                <span class="date">

                                    <i class="fa-regular fa-calendar"></i>

                                    <?= !empty(
                                        $row['tarikh_daftar']
                                    )
                                        ? date(
                                            "d M Y",
                                            strtotime(
                                                $row['tarikh_daftar']
                                            )
                                        )
                                        : "-"
                                    ?>

                                </span>

                            </td>


                            <!-- TINDAKAN -->

                            <td>

                                <div class="action-buttons">


                                    <!-- EDIT -->

                                    <a
                                        href="edit.php?id=<?= $row['id'] ?>"
                                        class="action-btn edit"
                                        title="Edit Pengguna"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                    </a>


                                    <!-- PADAM -->

                                    <a
                                        href="padam.php?id=<?= $row['id'] ?>"
                                        class="action-btn delete"
                                        title="Padam Pengguna"
                                        onclick="return confirm('Adakah anda pasti mahu memadam pengguna ini?');"
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
                                colspan="8"
                                class="empty-table"
                            >


                                <div class="empty-icon">

                                    <i class="fa-solid fa-users"></i>

                                </div>


                                <h3>
                                    Tiada Pengguna
                                </h3>


                                <p>
                                    Belum terdapat pengguna
                                    dalam sistem.
                                </p>


                                <a
                                    href="tambah.php"
                                    class="add-btn"
                                >

                                    <i class="fa-solid fa-user-plus"></i>

                                    Tambah Pengguna

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
                "searchUser"
            );

        const table =
            document.getElementById(
                "userTable"
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
                            text.includes(
                                keyword
                            )
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