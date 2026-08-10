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
   ELAK ADMIN PADAM AKAUN SENDIRI
========================================= */

if ($id === (int) $_SESSION['user_id']) {

    echo "
    <script>
        alert('Anda tidak boleh memadam akaun sendiri.');
        window.location.href = 'index.php';
    </script>
    ";

    exit();

}


/* =========================================
   AMBIL DATA PENGGUNA
========================================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, nama, email, role, gambar
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
   PADAM PENGGUNA
========================================= */

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM users WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);


    /* =====================================
       PADAM GAMBAR PROFIL
    ===================================== */

    if (!empty($user['gambar'])) {

        $gambarPath =
            "../../../uploads/profile/" . $user['gambar'];

        if (
            file_exists($gambarPath) &&
            is_file($gambarPath)
        ) {

            unlink($gambarPath);

        }

    }


    /* =====================================
       BERJAYA
    ===================================== */

    echo "
    <script>
        alert('Pengguna berjaya dipadam.');
        window.location.href = 'index.php';
    </script>
    ";

    exit();

}


/* =========================================
   GAGAL
========================================= */

$error = mysqli_error($conn);

mysqli_stmt_close($stmt);

echo "
<script>
    alert('Gagal memadam pengguna: " .
    htmlspecialchars($error, ENT_QUOTES) .
    "');
    window.location.href = 'index.php';
</script>
";

exit();

?>