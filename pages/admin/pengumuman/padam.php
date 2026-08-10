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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: index.php");
    exit();

}

$id = (int) $_GET['id'];


/* =================================================
   SEMAK PENGUMUMAN WUJUD
================================================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, tajuk FROM pengumuman WHERE id = ?"
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
   JIKA TIADA REKOD
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
   PADAM
================================================= */

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM pengumuman WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);


/* =================================================
   BERJAYA
================================================= */

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    echo "
    <script>

        alert('Pengumuman berjaya dipadam.');

        window.location='index.php';

    </script>
    ";

    exit();

}


/* =================================================
   GAGAL
================================================= */

$error = mysqli_error($conn);

mysqli_stmt_close($stmt);

echo "
<script>

    alert('Gagal memadam pengumuman.');

    window.location='index.php';

</script>
";

exit();

?>