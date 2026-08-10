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
    header("Location: ../../../login.php");
    exit();
}


/* =================================================
   DATABASE
================================================= */

include("../../../components/config.php");


/* =================================================
   NILAI DEFAULT
================================================= */

$kod_subjek   = '';
$nama_kelas   = '';
$program      = '';
$semester     = '';
$sesi         = '';
$pensyarah_id = '';
$hari         = '';
$masa_mula    = '';
$masa_tamat   = '';
$lokasi       = '';
$status       = 'aktif';

$error = '';


/* =================================================
   AMBIL SENARAI PENSYARAH
================================================= */

$pensyarahQuery = mysqli_query(
    $conn,
    "SELECT id, nama
     FROM users
     WHERE role = 'pensyarah'
     AND status = 'aktif'
     ORDER BY nama ASC"
);


/* =================================================
   PROSES FORM
================================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ---------------------------------------------
       AMBIL DATA FORM
    --------------------------------------------- */

    $kod_subjek = trim(
        $_POST['kod_subjek'] ?? ''
    );

    $nama_kelas = trim(
        $_POST['nama_kelas'] ?? ''
    );

    $program = trim(
        $_POST['program'] ?? ''
    );

    $semester = $_POST['semester'] !== ''
        ? (int) $_POST['semester']
        : null;

    $sesi = trim(
        $_POST['sesi'] ?? ''
    );

    $pensyarah_id = $_POST['pensyarah_id'] !== ''
        ? (int) $_POST['pensyarah_id']
        : 0;

    $hari = trim(
        $_POST['hari'] ?? ''
    );

    $masa_mula = trim(
        $_POST['masa_mula'] ?? ''
    );

    $masa_tamat = trim(
        $_POST['masa_tamat'] ?? ''
    );

    $lokasi = trim(
        $_POST['lokasi'] ?? ''
    );

    $status = $_POST['status'] ?? 'aktif';


    /* ---------------------------------------------
       VALIDASI
    --------------------------------------------- */

    if ($kod_subjek === '') {

        $error = "Sila masukkan kod subjek.";

    } elseif ($nama_kelas === '') {

        $error = "Sila masukkan nama kelas.";

    } elseif ($pensyarah_id <= 0) {

        $error = "Sila pilih pensyarah.";

    } elseif (
        !in_array(
            $status,
            ['aktif', 'tidak aktif'],
            true
        )
    ) {

        $error = "Status tidak sah.";

    }


    /* ---------------------------------------------
       SEMAK KOD SUBJEK
    --------------------------------------------- */

    if ($error === '') {

        $checkStmt = mysqli_prepare(
            $conn,
            "SELECT id
             FROM kelas
             WHERE kod_subjek = ?
             LIMIT 1"
        );

        if ($checkStmt) {

            mysqli_stmt_bind_param(
                $checkStmt,
                "s",
                $kod_subjek
            );

            mysqli_stmt_execute(
                $checkStmt
            );

            mysqli_stmt_store_result(
                $checkStmt
            );

            if (
                mysqli_stmt_num_rows(
                    $checkStmt
                ) > 0
            ) {

                $error =
                    "Kod subjek tersebut sudah digunakan.";

            }

            mysqli_stmt_close(
                $checkStmt
            );
        }
    }


    /* ---------------------------------------------
       INSERT DATABASE
    --------------------------------------------- */

    if ($error === '') {

        $sql = "
            INSERT INTO kelas
            (
                kod_subjek,
                nama_kelas,
                program,
                semester,
                sesi,
                pensyarah_id,
                hari,
                masa_mula,
                masa_tamat,
                lokasi,
                status
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ";


        $stmt = mysqli_prepare(
            $conn,
            $sql
        );


        if (!$stmt) {

            $error =
                "SQL Error: " .
                mysqli_error($conn);

        } else {

            /*
             * s = string
             * i = integer
             *
             * kod_subjek    = s
             * nama_kelas    = s
             * program       = s
             * semester      = i
             * sesi          = s
             * pensyarah_id  = i
             * hari          = s
             * masa_mula     = s
             * masa_tamat    = s
             * lokasi        = s
             * status        = s
             */

            mysqli_stmt_bind_param(
                $stmt,
                "sssisis ssss",
                $kod_subjek,
                $nama_kelas,
                $program,
                $semester,
                $sesi,
                $pensyarah_id,
                $hari,
                $masa_mula,
                $masa_tamat,
                $lokasi,
                $status
            );


            if (
                mysqli_stmt_execute(
                    $stmt
                )
            ) {

                mysqli_stmt_close(
                    $stmt
                );

                echo "
                    <script>
                        alert('Kelas berjaya ditambah!');
                        window.location.href = 'index.php';
                    </script>
                ";

                exit();

            } else {

                $error =
                    "Gagal menambah kelas: " .
                    mysqli_stmt_error($stmt);

                mysqli_stmt_close(
                    $stmt
                );
            }
        }
    }
}


/* =================================================
   ADMIN HEAD
================================================= */

$pageCss = "admin-akademik-tambah.css";

include(
    "../../../asset/admin/admin-components/adminHead.php"
);

?>


<body>


<!-- =================================================
     SIDEBAR
================================================= -->

<div class="admin-sidebar-overlay"></div>

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

                <i class="fa-solid fa-book-medical"></i>

                Akademik

            </span>


            <h1>
                Tambah Kelas
            </h1>


            <p>
                Tambah maklumat kelas akademik baharu
                ke dalam sistem IR-KVKS.
            </p>

        </div>


        <!-- KEMBALI -->

        <a
            href="index.php"
            class="back-btn"
        >

            <i class="fa-solid fa-arrow-left"></i>

            <span>Kembali</span>

        </a>

    </div>



    <!-- =================================================
         CONTENT
    ================================================== -->

    <div class="academic-form-content">


        <!-- =================================================
             ERROR
        ================================================== -->

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
        ================================================== -->

        <div class="academic-form-card">


            <!-- HEADER -->

            <div class="form-card-header">

                <div class="form-header-icon">

                    <i class="fa-solid fa-book-open"></i>

                </div>


                <div>

                    <h2>
                        Maklumat Kelas
                    </h2>

                    <p>
                        Masukkan maklumat akademik
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
                class="academic-form"
            >


                <!-- =================================================
                     KOD SUBJEK + NAMA KELAS
                ================================================== -->

                <div class="form-row">


                    <!-- KOD SUBJEK -->

                    <div class="form-group">

                        <label for="kod_subjek">

                            Kod Subjek

                            <span>*</span>

                        </label>


                        <div class="input-icon">

                            <i class="fa-solid fa-code"></i>

                            <input
                                type="text"
                                id="kod_subjek"
                                name="kod_subjek"
                                maxlength="30"
                                placeholder="Contoh: KPD3024"
                                required
                                value="<?= htmlspecialchars($kod_subjek) ?>"
                            >

                        </div>

                    </div>



                    <!-- NAMA KELAS -->

                    <div class="form-group">

                        <label for="nama_kelas">

                            Nama Kelas

                            <span>*</span>

                        </label>


                        <div class="input-icon">

                            <i class="fa-solid fa-chalkboard"></i>

                            <input
                                type="text"
                                id="nama_kelas"
                                name="nama_kelas"
                                maxlength="150"
                                placeholder="Contoh: Application Module Integration"
                                required
                                value="<?= htmlspecialchars($nama_kelas) ?>"
                            >

                        </div>

                    </div>


                </div>



                <!-- =================================================
                     PROGRAM
                ================================================== -->

                <div class="form-group">

                    <label for="program">

                        Program

                    </label>


                    <div class="input-icon">

                        <i class="fa-solid fa-graduation-cap"></i>

                        <input
                            type="text"
                            id="program"
                            name="program"
                            maxlength="100"
                            placeholder="Contoh: Sistem Pengurusan Pangkalan Data dan Aplikasi Web"
                            value="<?= htmlspecialchars($program) ?>"
                        >

                    </div>

                </div>



                <!-- =================================================
                     SEMESTER + SESI
                ================================================== -->

                <div class="form-row">


                    <!-- SEMESTER -->

                    <div class="form-group">

                        <label for="semester">

                            Semester

                        </label>


                        <div class="input-icon">

                            <i class="fa-solid fa-layer-group"></i>


                            <select
                                id="semester"
                                name="semester"
                            >

                                <option value="">

                                    -- Pilih Semester --

                                </option>


                                <?php
                                for (
                                    $i = 1;
                                    $i <= 8;
                                    $i++
                                ):
                                ?>

                                    <option
                                        value="<?= $i ?>"
                                        <?= (
                                            (string)$semester ===
                                            (string)$i
                                        )
                                            ? 'selected'
                                            : '' ?>
                                    >

                                        Semester <?= $i ?>

                                    </option>

                                <?php endfor; ?>

                            </select>

                        </div>

                    </div>



                    <!-- SESI -->

                    <div class="form-group">

                        <label for="sesi">

                            Sesi

                        </label>


                        <div class="input-icon">

                            <i class="fa-solid fa-calendar"></i>


                            <input
                                type="text"
                                id="sesi"
                                name="sesi"
                                maxlength="50"
                                placeholder="Contoh: 2026/2027"
                                value="<?= htmlspecialchars($sesi) ?>"
                            >

                        </div>

                    </div>


                </div>



                <!-- =================================================
                     PENSYARAH
                ================================================== -->

                <div class="form-group">

                    <label for="pensyarah_id">

                        Pensyarah

                        <span>*</span>

                    </label>


                    <div class="input-icon">

                        <i class="fa-solid fa-chalkboard-user"></i>


                        <select
                            id="pensyarah_id"
                            name="pensyarah_id"
                            required
                        >

                            <option value="">

                                -- Pilih Pensyarah --

                            </option>


                            <?php if (
                                $pensyarahQuery &&
                                mysqli_num_rows(
                                    $pensyarahQuery
                                ) > 0
                            ): ?>


                                <?php while (
                                    $pensyarah =
                                    mysqli_fetch_assoc(
                                        $pensyarahQuery
                                    )
                                ): ?>


                                    <option
                                        value="<?= $pensyarah['id'] ?>"
                                        <?= (
                                            (string)$pensyarah_id ===
                                            (string)$pensyarah['id']
                                        )
                                            ? 'selected'
                                            : '' ?>
                                    >

                                        <?= htmlspecialchars(
                                            $pensyarah['nama']
                                        ) ?>

                                    </option>


                                <?php endwhile; ?>


                            <?php else: ?>

                                <option
                                    value=""
                                    disabled
                                >

                                    Tiada pensyarah aktif tersedia

                                </option>

                            <?php endif; ?>


                        </select>

                    </div>

                </div>



                <!-- =================================================
                     HARI
                ================================================== -->

                <div class="form-group">

                    <label for="hari">

                        Hari

                    </label>


                    <div class="input-icon">

                        <i class="fa-solid fa-calendar-day"></i>


                        <select
                            id="hari"
                            name="hari"
                        >

                            <option value="">

                                -- Pilih Hari --

                            </option>


                            <?php

                            $hariList = [

                                'Isnin',
                                'Selasa',
                                'Rabu',
                                'Khamis',
                                'Jumaat',
                                'Sabtu',
                                'Ahad'

                            ];

                            ?>


                            <?php foreach (
                                $hariList as $hariItem
                            ): ?>

                                <option
                                    value="<?= $hariItem ?>"
                                    <?= $hari === $hariItem
                                        ? 'selected'
                                        : '' ?>
                                >

                                    <?= $hariItem ?>

                                </option>

                            <?php endforeach; ?>


                        </select>

                    </div>

                </div>



                <!-- =================================================
                     MASA MULA + MASA TAMAT
                ================================================== -->

                <div class="form-row">


                    <!-- MASA MULA -->

                    <div class="form-group">

                        <label for="masa_mula">

                            Masa Mula

                        </label>


                        <div class="input-icon">

                            <i class="fa-regular fa-clock"></i>


                            <input
                                type="time"
                                id="masa_mula"
                                name="masa_mula"
                                value="<?= htmlspecialchars($masa_mula) ?>"
                            >

                        </div>

                    </div>



                    <!-- MASA TAMAT -->

                    <div class="form-group">

                        <label for="masa_tamat">

                            Masa Tamat

                        </label>


                        <div class="input-icon">

                            <i class="fa-regular fa-clock"></i>


                            <input
                                type="time"
                                id="masa_tamat"
                                name="masa_tamat"
                                value="<?= htmlspecialchars($masa_tamat) ?>"
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

                    </label>


                    <div class="input-icon">

                        <i class="fa-solid fa-location-dot"></i>


                        <input
                            type="text"
                            id="lokasi"
                            name="lokasi"
                            maxlength="120"
                            placeholder="Contoh: Makmal Komputer 1"
                            value="<?= htmlspecialchars($lokasi) ?>"
                        >

                    </div>

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

                        <i class="fa-solid fa-circle-check"></i>


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
                                value="tidak aktif"
                                <?= $status === 'tidak aktif'
                                    ? 'selected'
                                    : '' ?>
                            >

                                Tidak Aktif

                            </option>

                        </select>

                    </div>

                </div>



                <!-- =================================================
                     INFO
                ================================================== -->

                <div class="form-info">

                    <i class="fa-solid fa-circle-info"></i>


                    <div>

                        <strong>
                            Maklumat penting
                        </strong>


                        <p>

                            Pastikan kod subjek,
                            nama kelas,
                            pensyarah dan
                            maklumat jadual
                            telah diisi dengan betul.

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

                        <i class="fa-solid fa-plus"></i>

                        Tambah Kelas

                    </button>


                </div>


            </form>


        </div>

    </div>


</div>


</body>

</html>