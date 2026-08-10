<?php
session_start();

/* =========================================================
   SECURITY
========================================================= */

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: ../../../login.php");
    exit();
}


/* =========================================================
   DATABASE
========================================================= */

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

/* =========================================================
   VARIABLES
========================================================= */

$error = "";

$nama = "";
$email = "";
$telefon = "";
$role = "";
$status = "aktif";


/* =========================================================
   PROCESS FORM
========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    /* =====================================================
       GET FORM DATA
    ===================================================== */

    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $telefon = trim($_POST['telefon'] ?? '');
    $status = $_POST['status'] ?? 'aktif';


    /* =====================================================
       VALIDATION
    ===================================================== */

    if (
        empty($nama) ||
        empty($email) ||
        empty($password) ||
        empty($role) ||
        empty($status)
    ) {

        $error = "Sila lengkapkan semua maklumat yang diperlukan.";

    }


    /* =====================================================
       EMAIL VALIDATION
    ===================================================== */

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Format email tidak sah.";

    }


    /* =====================================================
       PASSWORD VALIDATION
    ===================================================== */

    elseif (strlen($password) < 6) {

        $error = "Kata laluan mestilah sekurang-kurangnya 6 aksara.";

    }


    /* =====================================================
       ROLE VALIDATION
    ===================================================== */

    elseif (
        !in_array(
            $role,
            ['admin', 'pelajar', 'pensyarah']
        )
    ) {

        $error = "Role pengguna tidak sah.";

    }


    /* =====================================================
       STATUS VALIDATION
    ===================================================== */

    elseif (
        !in_array(
            $status,
            ['aktif', 'tidak aktif']
        )
    ) {

        $error = "Status pengguna tidak sah.";

    }


    /* =====================================================
       CHECK EMAIL
    ===================================================== */

    else {

        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ? LIMIT 1"
        );


        if (!$check) {

            $error =
                "Ralat database: " .
                mysqli_error($conn);

        } else {

            mysqli_stmt_bind_param(
                $check,
                "s",
                $email
            );

            mysqli_stmt_execute($check);

            $checkResult =
                mysqli_stmt_get_result($check);


            if (
                $checkResult &&
                mysqli_num_rows($checkResult) > 0
            ) {

                $error =
                    "Email tersebut sudah digunakan.";

            }


            mysqli_stmt_close($check);

        }

    }


    /* =====================================================
       UPLOAD IMAGE
    ===================================================== */

    $gambar = "";
    $uploadDir = "";


    if (empty($error)) {


        if (
            isset($_FILES['gambar']) &&
            $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE
        ) {


            $file = $_FILES['gambar'];


            /* =============================================
               CHECK UPLOAD ERROR
            ============================================= */

            if (
                $file['error'] !==
                UPLOAD_ERR_OK
            ) {

                $error =
                    "Gambar gagal dimuat naik.";

            }


            /* =============================================
               CHECK FILE SIZE
            ============================================= */

            elseif (
                $file['size'] >
                5 * 1024 * 1024
            ) {

                $error =
                    "Saiz gambar tidak boleh melebihi 5MB.";

            }


            /* =============================================
               CHECK MIME TYPE
            ============================================= */

            else {

                $allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];


                $imageInfo =
                    getimagesize(
                        $file['tmp_name']
                    );


                if ($imageInfo === false) {

                    $error =
                        "Fail yang dipilih bukan gambar yang sah.";

                }


                elseif (
                    !in_array(
                        $imageInfo['mime'],
                        $allowedTypes
                    )
                ) {

                    $error =
                        "Format gambar mesti JPG, PNG atau WEBP.";

                }

            }


            /* =============================================
               SAVE IMAGE
            ============================================= */

            if (empty($error)) {


                $uploadDir =
                    __DIR__ .
                    "/../../../uploads/profile/";


                /* CREATE FOLDER */

                if (!is_dir($uploadDir)) {

                    if (
                        !mkdir(
                            $uploadDir,
                            0777,
                            true
                        )
                    ) {

                        $error =
                            "Folder upload gambar gagal dicipta.";

                    }

                }


                /* =========================================
                   GENERATE UNIQUE FILE NAME
                ========================================= */

                if (empty($error)) {


                    $extension =
                        strtolower(
                            pathinfo(
                                $file['name'],
                                PATHINFO_EXTENSION
                            )
                        );


                    $gambar =
                        "profile_" .
                        time() .
                        "_" .
                        bin2hex(
                            random_bytes(5)
                        ) .
                        "." .
                        $extension;


                    $uploadPath =
                        $uploadDir .
                        $gambar;


                    /* =====================================
                       MOVE IMAGE
                    ===================================== */

                    if (
                        !move_uploaded_file(
                            $file['tmp_name'],
                            $uploadPath
                        )
                    ) {

                        $error =
                            "Gambar gagal disimpan.";

                        $gambar = "";

                    }

                }

            }

        }

    }


    /* =====================================================
       INSERT USER
    ===================================================== */

    if (empty($error)) {


        /* =============================================
           HASH PASSWORD
        ============================================= */

        $hashedPassword =
            password_hash(
                $password,
                PASSWORD_DEFAULT
            );


        /* =============================================
           PREPARE SQL
        ============================================= */

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO users
            (
                nama,
                email,
                password,
                role,
                gambar,
                telefon,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );


        if (!$stmt) {

            $error =
                "Ralat database: " .
                mysqli_error($conn);


        } else {


            mysqli_stmt_bind_param(
                $stmt,
                "sssssss",
                $nama,
                $email,
                $hashedPassword,
                $role,
                $gambar,
                $telefon,
                $status
            );


            /* =========================================
               EXECUTE
            ========================================= */

            if (
                mysqli_stmt_execute($stmt)
            ) {


                mysqli_stmt_close($stmt);


                echo "
                <script>
                    alert('Pengguna berjaya ditambah.');
                    window.location='index.php';
                </script>
                ";

                exit();


            } else {


                /* =====================================
                   DELETE IMAGE IF DATABASE FAILED
                ===================================== */

                if (
                    !empty($gambar) &&
                    !empty($uploadDir) &&
                    file_exists(
                        $uploadDir . $gambar
                    )
                ) {

                    unlink(
                        $uploadDir . $gambar
                    );

                }


                $error =
                    "Pengguna gagal ditambah: " .
                    mysqli_stmt_error($stmt);


                mysqli_stmt_close($stmt);

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
        Tambah Pengguna | IR-KVKS
    </title>


    <!-- =================================================
         ADMIN HEAD
    ================================================== -->

    <?php

    $pageCss = "admin-pengguna-tambah.css";

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

                <i class="fa-solid fa-users"></i>

                Pengguna

            </span>


            <h1>
                Tambah Pengguna
            </h1>


            <p>
                Tambah pengguna baharu ke dalam sistem IR-KVKS.
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

    <div class="user-form-content">


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

        <div class="user-form-card">


            <!-- FORM HEADER -->

            <div class="form-card-header">


                <div class="form-header-icon">

                    <i class="fa-solid fa-user-plus"></i>

                </div>


                <div>

                    <h2>
                        Maklumat Pengguna
                    </h2>


                    <p>
                        Masukkan maklumat pengguna dengan lengkap.
                    </p>

                </div>


            </div>



            <!-- =================================================
                 FORM
            ================================================== -->

            <form
                method="POST"
                action=""
                class="user-form"
                enctype="multipart/form-data"
            >


                <!-- =================================================
                     NAMA
                ================================================== -->

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
                            placeholder="Contoh: Ahmad bin Ali"
                            maxlength="150"
                            required
                            value="<?= htmlspecialchars($nama) ?>"
                        >


                    </div>


                </div>



                <!-- =================================================
                     GAMBAR
                ================================================== -->

                <div class="form-group">


                    <label for="gambar">

                        Gambar Profil

                    </label>


                    <div class="image-upload-box">


                        <!-- PREVIEW -->

                        <div class="image-preview">


                            <i class="fa-solid fa-user"></i>


                        </div>



                        <!-- CONTENT -->

                        <div class="image-upload-content">


                            <label
                                for="gambar"
                                class="upload-btn"
                            >

                                <i class="fa-solid fa-cloud-arrow-up"></i>

                                Pilih Gambar

                            </label>


                            <input
                                type="file"
                                id="gambar"
                                name="gambar"
                                accept=".jpg,.jpeg,.png,.webp"
                                hidden
                            >


                            <p id="fileName">

                                Belum ada gambar dipilih.

                            </p>


                            <small>

                                JPG, PNG atau WEBP.
                                Maksimum 5MB.

                            </small>


                        </div>


                    </div>


                </div>



                <!-- =================================================
                     EMAIL
                ================================================== -->

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
                            placeholder="Contoh: nama@email.com"
                            maxlength="150"
                            required
                            value="<?= htmlspecialchars($email) ?>"
                        >


                    </div>


                </div>



                <!-- =================================================
                     PASSWORD
                ================================================== -->

                <div class="form-group">


                    <label for="password">

                        Kata Laluan

                        <span>*</span>

                    </label>


                    <div class="input-icon password-input">


                        <i class="fa-solid fa-lock"></i>


                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimum 6 aksara"
                            minlength="6"
                            required
                        >


                        <button
                            type="button"
                            id="togglePassword"
                            class="password-toggle"
                        >

                            <i class="fa-solid fa-eye"></i>

                        </button>


                    </div>


                    <small>
                        Kata laluan mestilah sekurang-kurangnya 6 aksara.
                    </small>


                </div>



                <!-- =================================================
                     ROLE + STATUS
                ================================================== -->

                <div class="form-row">


                    <!-- ROLE -->

                    <div class="form-group">


                        <label for="role">

                            Role

                            <span>*</span>

                        </label>


                        <div class="input-icon">


                            <i class="fa-solid fa-user-tag"></i>


                            <select
                                id="role"
                                name="role"
                                required
                            >


                                <option
                                    value=""
                                >

                                    -- Pilih Role --

                                </option>


                                <option
                                    value="admin"
                                    <?= $role === 'admin'
                                        ? 'selected'
                                        : '' ?>
                                >

                                    Admin

                                </option>


                                <option
                                    value="pelajar"
                                    <?= $role === 'pelajar'
                                        ? 'selected'
                                        : '' ?>
                                >

                                    Pelajar

                                </option>


                                <option
                                    value="pensyarah"
                                    <?= $role === 'pensyarah'
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


                </div>



                <!-- =================================================
                     TELEFON
                ================================================== -->

                <div class="form-group">


                    <label for="telefon">

                        No. Telefon

                    </label>


                    <div class="input-icon">


                        <i class="fa-solid fa-phone"></i>


                        <input
                            type="text"
                            id="telefon"
                            name="telefon"
                            placeholder="Contoh: 0123456789"
                            maxlength="20"
                            value="<?= htmlspecialchars($telefon) ?>"
                        >


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

                            Pastikan maklumat pengguna
                            adalah betul sebelum menambah
                            pengguna.

                        </p>


                    </div>


                </div>



                <!-- =================================================
                     ACTION
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

                        <i class="fa-solid fa-user-plus"></i>

                        Tambah Pengguna

                    </button>


                </div>


            </form>


        </div>


    </div>


</div>



<!-- =================================================
     JAVASCRIPT
================================================== -->

<script>


/* =====================================================
   IMAGE PREVIEW
===================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {


        const imageInput =
            document.getElementById("gambar");


        const imagePreview =
            document.querySelector(".image-preview");


        const fileName =
            document.getElementById("fileName");



        if (imageInput) {


            imageInput.addEventListener(
                "change",
                function () {


                    const file =
                        this.files[0];


                    /* NO FILE */

                    if (!file) {


                        imagePreview.innerHTML =
                            '<i class="fa-solid fa-user"></i>';


                        fileName.textContent =
                            "Belum ada gambar dipilih.";


                        return;

                    }



                    /* CHECK SIZE */

                    if (
                        file.size >
                        5 * 1024 * 1024
                    ) {


                        alert(
                            "Saiz gambar tidak boleh melebihi 5MB."
                        );


                        this.value = "";


                        imagePreview.innerHTML =
                            '<i class="fa-solid fa-user"></i>';


                        fileName.textContent =
                            "Belum ada gambar dipilih.";


                        return;

                    }



                    /* FILE NAME */

                    fileName.textContent =
                        file.name;



                    /* PREVIEW */

                    const reader =
                        new FileReader();


                    reader.onload =
                        function (event) {


                            imagePreview.innerHTML = `

                                <img
                                    src="${event.target.result}"
                                    alt="Preview gambar"
                                >

                            `;

                        };


                    reader.readAsDataURL(file);

                }
            );

        }



        /* =================================================
           TOGGLE PASSWORD
        ================================================= */

        const password =
            document.getElementById("password");


        const togglePassword =
            document.getElementById("togglePassword");


        if (
            password &&
            togglePassword
        ) {


            togglePassword.addEventListener(
                "click",
                function () {


                    if (
                        password.type ===
                        "password"
                    ) {


                        password.type =
                            "text";


                        this.innerHTML =
                            '<i class="fa-solid fa-eye-slash"></i>';


                    } else {


                        password.type =
                            "password";


                        this.innerHTML =
                            '<i class="fa-solid fa-eye"></i>';

                    }

                }
            );

        }


    }
);

</script>


</body>

</html>