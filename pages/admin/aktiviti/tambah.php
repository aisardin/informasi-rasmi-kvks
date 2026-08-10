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
// =================================================
// PROSES TAMBAH AKTIVITI
// =================================================

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_aktiviti = trim($_POST['nama_aktiviti'] ?? '');
    $keterangan    = trim($_POST['keterangan'] ?? '');
    $tarikh        = $_POST['tarikh'] ?? '';
    $masa          = $_POST['masa'] ?? '';
    $lokasi        = trim($_POST['lokasi'] ?? '');
    $status        = $_POST['status'] ?? 'aktif';


    // VALIDASI

    if (
        empty($nama_aktiviti) ||
        empty($keterangan) ||
        empty($tarikh) ||
        empty($masa) ||
        empty($lokasi)
    ) {

        $error = "Sila lengkapkan semua maklumat yang diperlukan.";

    } elseif (!in_array($status, ['aktif', 'tamat'])) {

        $error = "Status aktiviti tidak sah.";

    } else {

        $sql = "
            INSERT INTO aktiviti
            (
                nama_aktiviti,
                keterangan,
                tarikh,
                masa,
                lokasi,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "ssssss",
                $nama_aktiviti,
                $keterangan,
                $tarikh,
                $masa,
                $lokasi,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {

                header("Location: index.php?success=1");
                exit();

            } else {

                $error = "Gagal menambah aktiviti: " .
                         mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);

        } else {

            $error = "Ralat SQL: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ms">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Tambah Aktiviti | IR-KVKS
    </title>


    <?php

    $pageCss = "admin-aktiviti-tambah.css";

    include(
        "../../../asset/admin/admin-components/adminHead.php"
    );

    ?>

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
    ================================================= -->

    <?php

    include(
        "../../../asset/admin/admin-components/admin-topbar.php"
    );

    ?>


    <!-- =================================================
         PAGE HEADER
    ================================================= -->

    <div class="page-header">

        <div>

            <span class="page-badge">

                <i class="fa-solid fa-calendar-plus"></i>

                Aktiviti

            </span>


            <h1>
                Tambah Aktiviti
            </h1>


            <p>
                Tambah aktiviti baharu untuk dipaparkan
                kepada pengguna IR-KVKS.
            </p>

        </div>


        <!-- KEMBALI -->

        <a
            href="index.php"
            class="back-btn"
        >

            <i class="fa-solid fa-arrow-left"></i>

            Kembali

        </a>

    </div>



    <!-- =================================================
         FORM CONTENT
    ================================================= -->

    <div class="activity-form-content">


        <!-- =================================================
             ERROR
        ================================================= -->

        <?php if (!empty($error)): ?>

            <div class="form-alert error">

                <i class="fa-solid fa-circle-exclamation"></i>

                <span>

                    <?= htmlspecialchars($error) ?>

                </span>

            </div>

        <?php endif; ?>



        <!-- =================================================
             FORM CARD
        ================================================= -->

        <div class="activity-form-card">


            <!-- FORM HEADER -->

            <div class="form-card-header">

                <div class="form-header-icon">

                    <i class="fa-solid fa-calendar-days"></i>

                </div>


                <div>

                    <h2>
                        Maklumat Aktiviti
                    </h2>

                    <p>
                        Masukkan maklumat aktiviti dengan lengkap.
                    </p>

                </div>

            </div>



            <!-- =================================================
                 FORM
            ================================================= -->

            <form
                method="POST"
                action=""
                class="activity-form"
            >


                <!-- NAMA AKTIVITI -->

                <div class="form-group">

                    <label for="nama_aktiviti">

                        Nama Aktiviti

                        <span>*</span>

                    </label>


                    <input
                        type="text"
                        id="nama_aktiviti"
                        name="nama_aktiviti"
                        maxlength="200"
                        placeholder="Contoh: Hari Sukan KVKS"
                        required
                        value="<?= htmlspecialchars(
                            $_POST['nama_aktiviti'] ?? ''
                        ) ?>"
                    >


                    <small>
                        Masukkan nama aktiviti dengan jelas.
                    </small>

                </div>



                <!-- KETERANGAN -->

                <div class="form-group">

                    <label for="keterangan">

                        Keterangan

                        <span>*</span>

                    </label>


                    <textarea
                        id="keterangan"
                        name="keterangan"
                        rows="7"
                        placeholder="Masukkan keterangan aktiviti..."
                        required
                    ><?= htmlspecialchars(
                        $_POST['keterangan'] ?? ''
                    ) ?></textarea>


                    <small>
                        Terangkan maklumat dan tujuan aktiviti.
                    </small>

                </div>



                <!-- TARIKH + MASA -->

                <div class="form-row">


                    <!-- TARIKH -->

                    <div class="form-group">

                        <label for="tarikh">

                            Tarikh

                            <span>*</span>

                        </label>


                        <div class="input-icon">

                            <i class="fa-regular fa-calendar"></i>


                            <input
                                type="date"
                                id="tarikh"
                                name="tarikh"
                                required
                                value="<?= htmlspecialchars(
                                    $_POST['tarikh'] ?? ''
                                ) ?>"
                            >

                        </div>

                    </div>



                    <!-- MASA -->

                    <div class="form-group">

                        <label for="masa">

                            Masa

                            <span>*</span>

                        </label>


                        <div class="input-icon">

                            <i class="fa-regular fa-clock"></i>


                            <input
                                type="time"
                                id="masa"
                                name="masa"
                                required
                                value="<?= htmlspecialchars(
                                    $_POST['masa'] ?? ''
                                ) ?>"
                            >

                        </div>

                    </div>

                </div>



                <!-- LOKASI -->

                <div class="form-group">

                    <label for="lokasi">

                        Lokasi

                        <span>*</span>

                    </label>


                    <div class="input-icon">

                        <i class="fa-solid fa-location-dot"></i>


                        <input
                            type="text"
                            id="lokasi"
                            name="lokasi"
                            maxlength="150"
                            placeholder="Contoh: Dewan Besar KVKS"
                            required
                            value="<?= htmlspecialchars(
                                $_POST['lokasi'] ?? ''
                            ) ?>"
                        >

                    </div>


                    <small>
                        Masukkan tempat aktiviti akan berlangsung.
                    </small>

                </div>



                <!-- STATUS -->

                <div class="form-group">

                    <label for="status">

                        Status

                        <span>*</span>

                    </label>


                    <div class="input-icon">

                        <i class="fa-solid fa-circle-check"></i>


                        <select
                            id="status"
                            name="status"
                            required
                        >

                            <option
                                value="aktif"
                                <?= (
                                    ($_POST['status'] ?? 'aktif')
                                    === 'aktif'
                                )
                                    ? 'selected'
                                    : ''
                                ?>
                            >
                                Aktif
                            </option>


                            <option
                                value="tamat"
                                <?= (
                                    ($_POST['status'] ?? '')
                                    === 'tamat'
                                )
                                    ? 'selected'
                                    : ''
                                ?>
                            >
                                Tamat
                            </option>

                        </select>

                    </div>


                    <small>
                        Aktiviti aktif akan dipaparkan kepada pengguna.
                    </small>

                </div>



                <!-- INFO -->

                <div class="form-info">

                    <i class="fa-solid fa-circle-info"></i>


                    <div>

                        <strong>
                            Maklumat penting
                        </strong>


                        <p>
                            Pastikan nama aktiviti, tarikh,
                            masa dan lokasi telah diisi dengan betul.
                        </p>

                    </div>

                </div>



                <!-- BUTTON -->

                <div class="form-actions">


                    <a
                        href="index.php"
                        class="cancel-btn"
                    >

                        Batal

                    </a>


                    <button
                        type="submit"
                        class="submit-btn"
                    >

                        <i class="fa-solid fa-plus"></i>

                        Tambah Aktiviti

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


<!-- =================================================
     JAVASCRIPT
================================================= -->

<script>

document.addEventListener("DOMContentLoaded", function () {

    const sidebar =
        document.querySelector(".admin-sidebar");

    const overlay =
        document.querySelector(".admin-sidebar-overlay");

    const toggle =
        document.querySelector(".sidebar-toggle");

    if (toggle && sidebar) {

        toggle.addEventListener("click", function () {

            sidebar.classList.toggle("active");

            if (overlay) {
                overlay.classList.toggle("active");
            }

        });

    }


    if (overlay && sidebar) {

        overlay.addEventListener("click", function () {

            sidebar.classList.remove("active");

            overlay.classList.remove("active");

        });

    }

});

</script>


</body>

</html>