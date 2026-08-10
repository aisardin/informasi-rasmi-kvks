<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../../login.php");
    exit();
}

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

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tajuk = trim($_POST['tajuk'] ?? '');
    $kandungan = trim($_POST['kandungan'] ?? '');
    $sasaran = $_POST['sasaran'] ?? '';
    $status = $_POST['status'] ?? 'aktif';

    // ==========================
    // VALIDATION
    // ==========================

    if (
        empty($tajuk) ||
        empty($kandungan) ||
        empty($sasaran) ||
        empty($status)
    ) {

        $error = "Sila lengkapkan semua maklumat.";

    } else {

        // ==========================
        // INSERT DATABASE
        // ==========================

        $sql = "INSERT INTO pengumuman
                (tajuk, kandungan, sasaran, status)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {

            $error = "SQL Error: " . mysqli_error($conn);

        } else {

            mysqli_stmt_bind_param(
                $stmt,
                "ssss",
                $tajuk,
                $kandungan,
                $sasaran,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_close($stmt);

                header("Location: index.php?success=1");
                exit();

            } else {

                $error = "Gagal menambah pengumuman: "
                       . mysqli_stmt_error($stmt);

                mysqli_stmt_close($stmt);
            }
        }
    }
}
$pageCss = "pengumuman-tambah.css";
include("../../../asset/admin/admin-components/adminHead.php");
?>

<body>

<!-- =====================================
     SIDEBAR OVERLAY
====================================== -->

<div class="admin-sidebar-overlay"></div>


<!-- =====================================
     SIDEBAR
====================================== -->

<?php
include("../../../asset/admin/admin-components/admin-sidebar.php");
?>


<!-- =====================================
     MAIN
====================================== -->

<div class="admin-main">


    <!-- =================================
     TOPBAR
================================== -->

<?php
include("../../../asset/admin/admin-components/admin-topbar.php");
?>


<!-- =================================
     PAGE HEADER
================================== -->

<div class="page-header">

    <div>

        <span class="page-badge">

            <i class="fa-solid fa-bullhorn"></i>

            Pengumuman

        </span>


        <h1>
            Tambah Pengumuman
        </h1>


        <p>
            Tambah pengumuman baharu untuk dipaparkan
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


<!-- =================================
     FORM CONTENT
================================== -->

<div class="announcement-form-content">


    <!-- ERROR -->

    <?php if (!empty($error)): ?>

        <div class="form-alert error">

            <i class="fa-solid fa-circle-exclamation"></i>

            <span>
                <?= htmlspecialchars($error) ?>
            </span>

        </div>

    <?php endif; ?>


    <!-- =================================
         FORM CARD
    ================================== -->

    <div class="announcement-form-card">


        <!-- FORM HEADER -->

        <div class="form-card-header">

            <div class="form-header-icon">

                <i class="fa-solid fa-bullhorn"></i>

            </div>


            <div>

                <h2>
                    Maklumat Pengumuman
                </h2>

                <p>
                    Masukkan maklumat pengumuman
                    dengan lengkap.
                </p>

            </div>

        </div>


        <!-- =================================
             FORM
        ================================== -->

        <form
            method="POST"
            action=""
            class="announcement-form"
        >


            <!-- =================================
                 TAJUK
            ================================== -->

            <div class="form-group">

                <label for="tajuk">

                    Tajuk Pengumuman

                    <span>*</span>

                </label>


                <input
                    type="text"
                    id="tajuk"
                    name="tajuk"
                    placeholder="Contoh: Pendaftaran Kursus Semester Baharu"
                    maxlength="200"
                    required
                    value="<?= htmlspecialchars($_POST['tajuk'] ?? '') ?>"
                >


                <small>
                    Masukkan tajuk ringkas dan jelas.
                </small>

            </div>


            <!-- =================================
                 KANDUNGAN
            ================================== -->

            <div class="form-group">

                <label for="kandungan">

                    Kandungan Pengumuman

                    <span>*</span>

                </label>


                <textarea
                    id="kandungan"
                    name="kandungan"
                    rows="8"
                    placeholder="Masukkan kandungan pengumuman..."
                    required
                ><?= htmlspecialchars($_POST['kandungan'] ?? '') ?></textarea>


                <small>
                    Terangkan maklumat pengumuman
                    dengan lengkap.
                </small>

            </div>


            <!-- =================================
                 GRID
            ================================== -->

            <div class="form-row">


                <!-- =================================
                     SASARAN
                ================================== -->

                <div class="form-group">

                    <label for="sasaran">

                        Sasaran Pengumuman

                        <span>*</span>

                    </label>


                    <div class="input-icon">

                        <i class="fa-solid fa-users"></i>


                        <select
                            id="sasaran"
                            name="sasaran"
                            required
                        >

                            <option value="">
                                -- Pilih Sasaran --
                            </option>


                            <option
                                value="semua"
                                <?= (($_POST['sasaran'] ?? '') == 'semua') ? 'selected' : '' ?>
                            >
                                Semua Pengguna
                            </option>


                            <option
                                value="pelajar"
                                <?= (($_POST['sasaran'] ?? '') == 'pelajar') ? 'selected' : '' ?>
                            >
                                Pelajar
                            </option>


                            <option
                                value="pensyarah"
                                <?= (($_POST['sasaran'] ?? '') == 'pensyarah') ? 'selected' : '' ?>
                            >
                                Pensyarah
                            </option>

                        </select>

                    </div>


                    <small>
                        Pilih pengguna yang boleh melihat pengumuman ini.
                    </small>

                </div>



                <!-- =================================
                     STATUS
                ================================== -->

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
                                <?= (($_POST['status'] ?? 'aktif') == 'aktif') ? 'selected' : '' ?>
                            >
                                Aktif
                            </option>


                            <option
                                value="tidak aktif"
                                <?= (($_POST['status'] ?? '') == 'tidak aktif') ? 'selected' : '' ?>
                            >
                                Tidak Aktif
                            </option>

                        </select>

                    </div>


                    <small>
                        Pengumuman aktif akan dipaparkan kepada sasaran.
                    </small>

                </div>


            </div>


            <!-- =================================
                 INFO
            ================================== -->

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
                        <b>sasaran</b> yang dipilih.

                    </p>

                </div>

            </div>


            <!-- =================================
                 TARIKH CIPTA
            ================================== -->

            <div class="form-info">

                <i class="fa-regular fa-clock"></i>

                <div>

                    <strong>
                        Tarikh penerbitan
                    </strong>

                    <p>
                        Tarikh dan masa pengumuman akan
                        direkodkan secara automatik oleh sistem.
                    </p>

                </div>

            </div>


            <!-- =================================
                 BUTTON
            ================================== -->

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

                    Tambah Pengumuman

                </button>


            </div>


        </form>

    </div>

</div>

</div>


</body>

</html>