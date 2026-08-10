<?php

session_start();

/* =========================================
   SEMAK LOGIN ADMIN
========================================= */

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: ../../../login.php");
    exit();
}


/* =========================================
   DATABASE
========================================= */

include("../../../components/config.php");


/* =========================================
   SEMAK ID
========================================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: index.php");
    exit();

}

$id = (int) $_GET['id'];


/* =========================================
   AMBIL DATA PENGGUNA
========================================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM users
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


/* =========================================
   PENGGUNA TIDAK DIJUMPAI
========================================= */

if (!$user) {

    echo "
    <script>
        alert('Pengguna tidak dijumpai.');
        window.location.href = 'index.php';
    </script>
    ";

    exit();

}


/* =========================================
   PROSES UPDATE
========================================= */

$error = "";
$success = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $status = trim($_POST['status'] ?? '');



    /* =====================================
       VALIDASI
    ===================================== */

    if (
        empty($nama) ||
        empty($email) ||
        empty($role) ||
        empty($status)
    ) {

        $error = "Sila lengkapkan semua maklumat yang diperlukan.";

    }


    /* =====================================
       SEMAK EMAIL
    ===================================== */

    if (empty($error)) {

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id
             FROM users
             WHERE email = ?
             AND id != ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $email,
            $id
        );

        mysqli_stmt_execute($stmt);

        $emailResult = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($emailResult) > 0) {

            $error = "Email tersebut sudah digunakan oleh pengguna lain.";

        }

        mysqli_stmt_close($stmt);

    }


    /* =====================================
       UPLOAD GAMBAR
    ===================================== */

    $gambarBaru = $user['gambar'] ?? '';

    if (
        empty($error) &&
        isset($_FILES['gambar']) &&
        $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $gambar = $_FILES['gambar'];

        /* Saiz maksimum 2MB */

        if ($gambar['size'] > 2 * 1024 * 1024) {

            $error = "Saiz gambar tidak boleh melebihi 2MB.";

        }


        /* Jenis fail */

        $allowedTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/webp'
        ];

        if (
            empty($error) &&
            !in_array($gambar['type'], $allowedTypes)
        ) {

            $error = "Format gambar mestilah JPG, PNG atau WEBP.";

        }


        /* =================================
           SIMPAN GAMBAR
        ================================= */

        if (empty($error)) {

            $extension = strtolower(
                pathinfo(
                    $gambar['name'],
                    PATHINFO_EXTENSION
                )
            );


            $namaFail = 'profile_' .
                        $id . '_' .
                        time() .
                        '.' .
                        $extension;


            $folder = "../../../uploads/profile/";


            /* Pastikan folder wujud */

            if (!is_dir($folder)) {

                mkdir(
                    $folder,
                    0777,
                    true
                );

            }


            $destination =
                $folder . $namaFail;


            if (
                move_uploaded_file(
                    $gambar['tmp_name'],
                    $destination
                )
            ) {

                /*
                 * Jika berjaya upload,
                 * simpan nama gambar baharu
                 */

                $gambarBaru = $namaFail;


                /* =========================
                   PADAM GAMBAR LAMA
                ========================= */

                if (
                    !empty($user['gambar'])
                ) {

                    $gambarLama =
                        $folder . $user['gambar'];

                    if (
                        file_exists($gambarLama) &&
                        is_file($gambarLama)
                    ) {

                        unlink($gambarLama);

                    }

                }

            } else {

                $error =
                    "Gagal memuat naik gambar.";

            }

        }

    }


    /* =====================================
       UPDATE DATABASE
    ===================================== */

    if (empty($error)) {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE users
             SET nama = ?,
                 email = ?,
                 role = ?,
                 gambar = ?,
                 telefon = ?,
                 status = ?
             WHERE id = ?"
        );


        mysqli_stmt_bind_param(
            $stmt,
            "ssssssi",
            $nama,
            $email,
            $role,
            $gambarBaru,
            $telefon,
            $status,
            $id
        );


        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_close($stmt);


            echo "
            <script>
                alert('Maklumat pengguna berjaya dikemaskini.');
                window.location.href = 'index.php';
            </script>
            ";

            exit();

        } else {

            $error =
                "Gagal mengemaskini pengguna: " .
                mysqli_error($conn);

        }


        mysqli_stmt_close($stmt);

    }

}

?>

<?php
$pageCss = "admin-pengguna-edit.css";

include(
    "../../../asset/admin/admin-components/adminHead.php"
);
?>

<body>

<?php
include(
    "../../../asset/admin/admin-components/admin-sidebar.php"
);
?>


<div class="admin-main">

    <?php
    include(
        "../../../asset/admin/admin-components/admin-topbar.php"
    );
    ?>


    <!-- =========================================
         PAGE HEADER
    ========================================== -->

    <div class="page-header">

        <div>

            <span class="page-badge">

                <i class="fa-solid fa-user-pen"></i>

                Pengguna

            </span>


            <h1>
                Edit Pengguna
            </h1>


            <p>
                Kemaskini maklumat pengguna IR-KVKS.
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



    <!-- =========================================
         FORM
    ========================================== -->

    <div class="user-edit-content">


        <?php if (!empty($error)): ?>

            <div class="form-alert error">

                <i class="fa-solid fa-circle-exclamation"></i>

                <span>
                    <?= htmlspecialchars($error) ?>
                </span>

            </div>

        <?php endif; ?>


        <div class="user-edit-card">


            <!-- HEADER -->

            <div class="form-card-header">

                <div class="form-header-icon">

                    <i class="fa-solid fa-user-pen"></i>

                </div>


                <div>

                    <h2>
                        Maklumat Pengguna
                    </h2>

                    <p>
                        Kemaskini maklumat akaun pengguna.
                    </p>

                </div>

            </div>



            <!-- FORM -->

            <form
                method="POST"
                enctype="multipart/form-data"
                class="user-edit-form"
            >


                <!-- =================================
                     GAMBAR
                ================================== -->

                <div class="profile-upload-section">


                    <div class="current-profile">


                        <?php if (!empty($user['gambar'])): ?>

                            <img
                                src="../../../uploads/profile/<?= htmlspecialchars($user['gambar']) ?>"
                                id="previewImage"
                                alt="Gambar Profil"
                            >

                        <?php else: ?>

                            <div
                                class="profile-placeholder"
                                id="profilePlaceholder"
                            >

                                <i class="fa-solid fa-user"></i>

                            </div>

                            <img
                                src=""
                                id="previewImage"
                                alt="Preview"
                                style="display:none;"
                            >

                        <?php endif; ?>


                    </div>


                    <div class="upload-info">

                        <h3>
                            Gambar Profil
                        </h3>

                        <p>
                            Tukar gambar profil pengguna.
                        </p>


                        <label
                            for="gambar"
                            class="upload-btn"
                        >

                            <i class="fa-solid fa-camera"></i>

                            Pilih Gambar

                        </label>


                        <input
                            type="file"
                            id="gambar"
                            name="gambar"
                            accept="image/jpeg,image/png,image/webp"
                            hidden
                        >


                        <small>
                            JPG, PNG atau WEBP. Maksimum 2MB.
                        </small>

                    </div>

                </div>



                <!-- =================================
                     NAMA
                ================================== -->

                <div class="form-group">

                    <label for="nama">

                        Nama Penuh

                        <span>*</span>

                    </label>


                    <div class="input-icon">

                        <i class="fa-solid fa-user"></i>


                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            maxlength="100"
                            required
                            value="<?= htmlspecialchars(
                                $_POST['nama']
                                ?? $user['nama']
                                ?? ''
                            ) ?>"
                            placeholder="Masukkan nama penuh"
                        >

                    </div>

                </div>



                <!-- =================================
                     EMAIL
                ================================== -->

                <div class="form-group">

                    <label for="email">

                        Email

                        <span>*</span>

                    </label>


                    <div class="input-icon">

                        <i class="fa-solid fa-envelope"></i>


                        <input
                            type="email"
                            id="email"
                            name="email"
                            maxlength="150"
                            required
                            value="<?= htmlspecialchars(
                                $_POST['email']
                                ?? $user['email']
                                ?? ''
                            ) ?>"
                            placeholder="contoh@email.com"
                        >

                    </div>

                </div>



                <!-- =================================
                     TELEFON
                ================================== -->

                <div class="form-group">

                    <label for="telefon">

                        Nombor Telefon

                    </label>


                    <div class="input-icon">

                        <i class="fa-solid fa-phone"></i>


                        <input
                            type="text"
                            id="telefon"
                            name="telefon"
                            maxlength="20"
                            value="<?= htmlspecialchars(
                                $_POST['telefon']
                                ?? $user['telefon']
                                ?? ''
                            ) ?>"
                            placeholder="Contoh: 0123456789"
                        >

                    </div>

                </div>



                <!-- =================================
                     ROLE + STATUS
                ================================== -->

                <div class="form-row">


                    <!-- ROLE -->

                    <div class="form-group">

                        <label for="role">

                            Role

                            <span>*</span>

                        </label>


                        <div class="input-icon">

                            <i class="fa-solid fa-user-shield"></i>


                            <select
                                id="role"
                                name="role"
                                required
                            >

                                <?php
                                $currentRole =
                                    $_POST['role']
                                    ?? $user['role']
                                    ?? '';
                                ?>


                                <option
                                    value=""
                                >
                                    -- Pilih Role --
                                </option>


                                <option
                                    value="admin"
                                    <?= $currentRole === 'admin'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Admin
                                </option>


                                <option
                                    value="pensyarah"
                                    <?= $currentRole === 'pensyarah'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Pensyarah
                                </option>


                                <option
                                    value="pelajar"
                                    <?= $currentRole === 'pelajar'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Pelajar
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


                            <?php
                            $currentStatus =
                                $_POST['status']
                                ?? $user['status']
                                ?? 'aktif';
                            ?>


                            <select
                                id="status"
                                name="status"
                                required
                            >

                                <option
                                    value="aktif"
                                    <?= $currentStatus === 'aktif'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Aktif
                                </option>


                                <option
                                    value="tidak aktif"
                                    <?= $currentStatus === 'tidak aktif'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Tidak Aktif
                                </option>

                            </select>

                        </div>

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
                            Pastikan maklumat pengguna
                            adalah betul sebelum menyimpan.
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

                        <i class="fa-solid fa-floppy-disk"></i>

                        Simpan Perubahan

                    </button>


                </div>


            </form>

        </div>

    </div>

</div>



<!-- =========================================
     PREVIEW GAMBAR
========================================= -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const input =
            document.getElementById("gambar");

        const preview =
            document.getElementById("previewImage");

        const placeholder =
            document.getElementById("profilePlaceholder");


        if (!input || !preview) {
            return;
        }


        input.addEventListener(
            "change",
            function () {

                const file =
                    this.files[0];


                if (!file) {
                    return;
                }


                const reader =
                    new FileReader();


                reader.onload =
                    function (event) {

                        preview.src =
                            event.target.result;

                        preview.style.display =
                            "block";


                        if (placeholder) {

                            placeholder.style.display =
                                "none";

                        }

                    };


                reader.readAsDataURL(file);

            }
        );

    }
);

</script>

</body>
</html>