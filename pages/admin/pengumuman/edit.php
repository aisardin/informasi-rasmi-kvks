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
   SEMAK ID
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
   AMBIL DATA PENGUMUMAN
================================================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, tajuk, kandungan, sasaran, status, tarikh_cipta
     FROM pengumuman
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$pengumuman = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


/* =================================================
   JIKA DATA TIADA
================================================= */

if (!$pengumuman) {

    echo "
    <script>
        alert('Pengumuman tidak dijumpai.');
        window.location='index.php';
    </script>
    ";

    exit();
}


/* =================================================
   UPDATE DATA
================================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tajuk = trim($_POST['tajuk'] ?? '');
    $kandungan = trim($_POST['kandungan'] ?? '');
    $sasaran = $_POST['sasaran'] ?? '';
    $status = $_POST['status'] ?? '';


    /* =============================================
       VALIDATION
    ============================================= */

    if (
        $tajuk === '' ||
        $kandungan === '' ||
        $sasaran === '' ||
        $status === ''
    ) {

        $error = "Sila lengkapkan semua maklumat.";

    } elseif (
        !in_array(
            $sasaran,
            ['semua', 'pelajar', 'pensyarah']
        )
    ) {

        $error = "Sasaran tidak sah.";

    } elseif (
        !in_array(
            $status,
            ['aktif', 'tidak aktif']
        )
    ) {

        $error = "Status tidak sah.";

    } else {


        /* =========================================
           UPDATE
        ========================================= */

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE pengumuman
             SET tajuk = ?,
                 kandungan = ?,
                 sasaran = ?,
                 status = ?
             WHERE id = ?"
        );


        mysqli_stmt_bind_param(
            $stmt,
            "ssssi",
            $tajuk,
            $kandungan,
            $sasaran,
            $status,
            $id
        );


        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_close($stmt);

            echo "
            <script>

                alert('Pengumuman berjaya dikemaskini.');

                window.location='index.php';

            </script>
            ";

            exit();

        } else {

            $error = "Gagal mengemaskini pengumuman.";

        }

        mysqli_stmt_close($stmt);
    }


    /* =========================================
       KEKALKAN INPUT JIKA ERROR
    ========================================= */

    $pengumuman['tajuk'] = $tajuk;
    $pengumuman['kandungan'] = $kandungan;
    $pengumuman['sasaran'] = $sasaran;
    $pengumuman['status'] = $status;
}

?>

<?php
$pageCss = "edit-pengumuman.css";
include("../../../asset/admin/admin-components/adminHead.php");?>


<!-- =================================================
     MAIN
================================================= -->

<div class="admin-main">

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
                Edit Pengumuman
            </h1>


            <p>
                Kemaskini maklumat pengumuman
                IR-KVKS.
            </p>

        </div>


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

    <div class="announcement-form-content">


        <!-- ERROR -->

        <?php if (isset($error)): ?>

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

        <div class="announcement-form-card">


            <!-- HEADER -->

            <div class="form-card-header">

                <div class="form-header-icon">

                    <i class="fa-solid fa-pen"></i>

                </div>


                <div>

                    <h2>
                        Maklumat Pengumuman
                    </h2>

                    <p>
                        Kemaskini maklumat di bawah.
                    </p>

                </div>

            </div>


            <!-- =================================================
                 FORM
            ================================================== -->

            <form
                method="POST"
                action=""
                class="announcement-form"
            >


                <!-- TAJUK -->

                <div class="form-group">

                    <label for="tajuk">

                        Tajuk Pengumuman

                        <span>*</span>

                    </label>


                    <input
                        type="text"
                        id="tajuk"
                        name="tajuk"
                        maxlength="200"
                        required
                        value="<?= htmlspecialchars(
                            $pengumuman['tajuk']
                        ) ?>"
                    >


                    <small>
                        Masukkan tajuk pengumuman.
                    </small>

                </div>


                <!-- KANDUNGAN -->

                <div class="form-group">

                    <label for="kandungan">

                        Kandungan

                        <span>*</span>

                    </label>


                    <textarea
                        id="kandungan"
                        name="kandungan"
                        rows="8"
                        required
                    ><?= htmlspecialchars(
                        $pengumuman['kandungan']
                    ) ?></textarea>


                    <small>
                        Masukkan kandungan penuh pengumuman.
                    </small>

                </div>


                <!-- =================================================
                     GRID
                ================================================== -->

                <div class="form-row">


                    <!-- SASARAN -->

                    <div class="form-group">

                        <label for="sasaran">

                            Sasaran

                            <span>*</span>

                        </label>


                        <div class="input-icon">

                            <i class="fa-solid fa-users"></i>


                            <select
                                id="sasaran"
                                name="sasaran"
                                required
                            >

                                <option
                                    value="semua"
                                    <?= $pengumuman['sasaran'] === 'semua'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Semua Pengguna
                                </option>


                                <option
                                    value="pelajar"
                                    <?= $pengumuman['sasaran'] === 'pelajar'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Pelajar
                                </option>


                                <option
                                    value="pensyarah"
                                    <?= $pengumuman['sasaran'] === 'pensyarah'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Pensyarah
                                </option>

                            </select>

                        </div>

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
                                    <?= $pengumuman['status'] === 'aktif'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Aktif
                                </option>


                                <option
                                    value="tidak aktif"
                                    <?= $pengumuman['status'] === 'tidak aktif'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Tidak Aktif
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     TARIKH CIPTA
                ================================================== -->

                <div class="created-info">

                    <i class="fa-regular fa-calendar"></i>

                    <div>

                        <strong>
                            Tarikh Dicipta
                        </strong>

                        <span>

                            <?= date(
                                "d M Y, h:i A",
                                strtotime(
                                    $pengumuman['tarikh_cipta']
                                )
                            ) ?>

                        </span>

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

                            Pengumuman berstatus
                            <b>Aktif</b> akan dipaparkan
                            kepada pengguna berdasarkan
                            sasaran yang dipilih.

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

                        <i class="fa-solid fa-floppy-disk"></i>

                        Simpan Perubahan

                    </button>


                </div>


            </form>

        </div>

    </div>


</div>


</body>

</html>