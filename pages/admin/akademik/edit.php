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
   AMBIL ID
================================================= */

if (
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
) {

    header("Location: index.php");
    exit();

}

$id = (int) $_GET['id'];


/* =================================================
   AMBIL DATA AKTIVITI
================================================= */

$sql = "
    SELECT *
    FROM aktiviti
    WHERE id = ?
";

$stmt = mysqli_prepare(
    $conn,
    $sql
);

if (!$stmt) {

    die(
        "Ralat database: "
        . mysqli_error($conn)
    );

}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result =
    mysqli_stmt_get_result($stmt);


if (
    !$result ||
    mysqli_num_rows($result) === 0
) {

    mysqli_stmt_close($stmt);

    header("Location: index.php");
    exit();

}


$aktiviti =
    mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


/* =================================================
   NILAI ASAL
================================================= */

$nama_aktiviti =
    $aktiviti['nama_aktiviti'] ?? '';

$keterangan =
    $aktiviti['keterangan'] ?? '';

$tarikh =
    $aktiviti['tarikh'] ?? '';

$masa =
    $aktiviti['masa'] ?? '';

$lokasi =
    $aktiviti['lokasi'] ?? '';

$status =
    $aktiviti['status'] ?? 'aktif';


/* =================================================
   UPDATE DATA
================================================= */

$error = '';

$success = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    /* =============================================
       AMBIL DATA FORM
    ============================================== */

    $nama_aktiviti =
        trim($_POST['nama_aktiviti'] ?? '');

    $keterangan =
        trim($_POST['keterangan'] ?? '');

    $tarikh =
        $_POST['tarikh'] ?? '';

    $masa =
        $_POST['masa'] ?? '';

    $lokasi =
        trim($_POST['lokasi'] ?? '');

    $status =
        $_POST['status'] ?? 'aktif';


    /* =============================================
       VALIDASI
    ============================================== */

    if ($nama_aktiviti === '') {

        $error =
            "Nama aktiviti wajib diisi.";

    } elseif ($keterangan === '') {

        $error =
            "Keterangan aktiviti wajib diisi.";

    } elseif ($tarikh === '') {

        $error =
            "Tarikh aktiviti wajib diisi.";

    } elseif ($masa === '') {

        $error =
            "Masa aktiviti wajib diisi.";

    } elseif ($lokasi === '') {

        $error =
            "Lokasi aktiviti wajib diisi.";

    } elseif (
        !in_array(
            $status,
            ['aktif', 'tamat'],
            true
        )
    ) {

        $error =
            "Status tidak sah.";

    }


    /* =============================================
       UPDATE
    ============================================== */

    if ($error === '') {


        $updateSql = "
            UPDATE aktiviti

            SET
                nama_aktiviti = ?,
                keterangan = ?,
                tarikh = ?,
                masa = ?,
                lokasi = ?,
                status = ?

            WHERE id = ?
        ";


        $updateStmt =
            mysqli_prepare(
                $conn,
                $updateSql
            );


        if (!$updateStmt) {

            $error =
                "Ralat menyediakan query: "
                . mysqli_error($conn);

        } else {


            mysqli_stmt_bind_param(
                $updateStmt,
                "ssssssi",
                $nama_aktiviti,
                $keterangan,
                $tarikh,
                $masa,
                $lokasi,
                $status,
                $id
            );


            if (
                mysqli_stmt_execute(
                    $updateStmt
                )
            ) {


                mysqli_stmt_close(
                    $updateStmt
                );


                header(
                    "Location: index.php?updated=success"
                );

                exit();


            } else {


                $error =
                    "Gagal mengemaskini aktiviti: "
                    . mysqli_stmt_error(
                        $updateStmt
                    );


                mysqli_stmt_close(
                    $updateStmt
                );

            }

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
        Edit Aktiviti | IR-KVKS
    </title>


    <!-- ADMIN HEAD -->

    <?php

    $pageCss =
        "admin-aktiviti-edit.css";

    include(
        "../../../asset/admin/admin-components/adminHead.php"
    );

    ?>

</head>


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

                <i class="fa-solid fa-calendar-pen"></i>

                Aktiviti

            </span>


            <h1>
                Edit Aktiviti
            </h1>


            <p>
                Kemaskini maklumat aktiviti
                dalam sistem IR-KVKS.
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
    ================================================== -->

    <div class="activity-edit-content">


        <!-- =================================================
             ERROR
        ================================================== -->

        <?php if ($error !== ''): ?>

            <div class="form-alert error">

                <i
                    class="fa-solid fa-circle-exclamation"
                ></i>

                <span>

                    <?= htmlspecialchars(
                        $error
                    ) ?>

                </span>

            </div>

        <?php endif; ?>



        <!-- =================================================
             FORM CARD
        ================================================== -->

        <div class="activity-edit-card">


            <!-- FORM HEADER -->

            <div class="form-card-header">


                <div class="form-header-icon">

                    <i
                        class="fa-solid fa-calendar-days"
                    ></i>

                </div>


                <div>

                    <h2>
                        Maklumat Aktiviti
                    </h2>

                    <p>
                        Kemaskini maklumat aktiviti
                        dengan lengkap.
                    </p>

                </div>


            </div>



            <!-- =================================================
                 FORM
            ================================================== -->

            <form
                method="POST"
                action=""
                class="activity-edit-form"
            >


                <!-- =================================================
                     NAMA AKTIVITI
                ================================================== -->

                <div class="form-group">


                    <label for="nama_aktiviti">

                        Nama Aktiviti

                        <span>*</span>

                    </label>


                    <div class="input-icon">

                        <i
                            class="fa-solid fa-calendar-days"
                        ></i>


                        <input
                            type="text"
                            id="nama_aktiviti"
                            name="nama_aktiviti"
                            maxlength="200"
                            required
                            placeholder="Contoh: Hari Sukan KVKS"
                            value="<?= htmlspecialchars(
                                $nama_aktiviti
                            ) ?>"
                        >

                    </div>


                    <small>
                        Masukkan nama aktiviti dengan jelas.
                    </small>


                </div>



                <!-- =================================================
                     KETERANGAN
                ================================================== -->

                <div class="form-group">


                    <label for="keterangan">

                        Keterangan

                        <span>*</span>

                    </label>


                    <textarea
                        id="keterangan"
                        name="keterangan"
                        rows="7"
                        required
                        placeholder="Masukkan keterangan aktiviti..."
                    ><?= htmlspecialchars(
                        $keterangan
                    ) ?></textarea>


                    <small>
                        Terangkan maklumat dan tujuan aktiviti.
                    </small>


                </div>



                <!-- =================================================
                     TARIKH + MASA
                ================================================== -->

                <div class="form-row">


                    <!-- TARIKH -->

                    <div class="form-group">


                        <label for="tarikh">

                            Tarikh

                            <span>*</span>

                        </label>


                        <div class="input-icon">

                            <i
                                class="fa-regular fa-calendar"
                            ></i>


                            <input
                                type="date"
                                id="tarikh"
                                name="tarikh"
                                required
                                value="<?= htmlspecialchars(
                                    $tarikh
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

                            <i
                                class="fa-regular fa-clock"
                            ></i>


                            <input
                                type="time"
                                id="masa"
                                name="masa"
                                required
                                value="<?= htmlspecialchars(
                                    substr(
                                        $masa,
                                        0,
                                        5
                                    )
                                ) ?>"
                            >

                        </div>


                    </div>


                </div>



                <!-- =================================================
                     LOKASI
                ================================================== -->

                <div class="form-group">


                    <label for="lokasi">

                        Lokasi

                        <span>*</span>

                    </label>


                    <div class="input-icon">

                        <i
                            class="fa-solid fa-location-dot"
                        ></i>


                        <input
                            type="text"
                            id="lokasi"
                            name="lokasi"
                            maxlength="150"
                            required
                            placeholder="Contoh: Dewan Besar KVKS"
                            value="<?= htmlspecialchars(
                                $lokasi
                            ) ?>"
                        >

                    </div>


                    <small>
                        Masukkan lokasi aktiviti.
                    </small>


                </div>



                <!-- =================================================
                     STATUS
                ================================================== -->

                <div class="form-group">


                    <label for="status">

                        Status

                        <span>*</span>

                    </label>


                    <div class="input-icon">

                        <i
                            class="fa-solid fa-circle-check"
                        ></i>


                        <select
                            id="status"
                            name="status"
                            required
                        >


                            <option
                                value="aktif"
                                <?= $status === 'aktif'
                                    ? 'selected'
                                    : '' ?>
                            >

                                Aktif

                            </option>


                            <option
                                value="tamat"
                                <?= $status === 'tamat'
                                    ? 'selected'
                                    : '' ?>
                            >

                                Tamat

                            </option>


                        </select>


                    </div>


                    <small>

                        Aktif = aktiviti masih berlangsung.
                        Tamat = aktiviti telah selesai.

                    </small>


                </div>



                <!-- =================================================
                     INFO
                ================================================== -->

                <div class="form-info">


                    <i
                        class="fa-solid fa-circle-info"
                    ></i>


                    <div>


                        <strong>
                            Maklumat penting
                        </strong>


                        <p>

                            Pastikan nama aktiviti,
                            tarikh, masa, lokasi dan
                            status telah diisi dengan betul.

                        </p>


                    </div>


                </div>



                <!-- =================================================
                     BUTTON
                ================================================== -->

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

                        <i
                            class="fa-solid fa-floppy-disk"
                        ></i>

                        Simpan Perubahan

                    </button>


                </div>


            </form>


        </div>


    </div>


</div>


</body>

</html>