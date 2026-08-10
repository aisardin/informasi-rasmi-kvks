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
   CONFIG DATABASE
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
   SEMAK AKTIVITI WUJUD
================================================= */

$checkSql = "
    SELECT id, nama_aktiviti
    FROM aktiviti
    WHERE id = ?
";

$checkStmt = mysqli_prepare(
    $conn,
    $checkSql
);


if (!$checkStmt) {

    die(
        "Ralat menyediakan query: "
        . mysqli_error($conn)
    );

}


mysqli_stmt_bind_param(
    $checkStmt,
    "i",
    $id
);


mysqli_stmt_execute($checkStmt);


$checkResult =
    mysqli_stmt_get_result($checkStmt);


if (
    !$checkResult ||
    mysqli_num_rows($checkResult) === 0
) {

    mysqli_stmt_close($checkStmt);

    header("Location: index.php");
    exit();

}


$aktiviti =
    mysqli_fetch_assoc($checkResult);


mysqli_stmt_close($checkStmt);


/* =================================================
   PADAM AKTIVITI
================================================= */

$deleteSql = "
    DELETE FROM aktiviti
    WHERE id = ?
";

$deleteStmt = mysqli_prepare(
    $conn,
    $deleteSql
);


if (!$deleteStmt) {

    die(
        "Ralat menyediakan query padam: "
        . mysqli_error($conn)
    );

}


mysqli_stmt_bind_param(
    $deleteStmt,
    "i",
    $id
);


if (
    mysqli_stmt_execute($deleteStmt)
) {

    mysqli_stmt_close($deleteStmt);

    header("Location: index.php?deleted=success");
    exit();

}


/* =================================================
   JIKA GAGAL
================================================= */

$error =
    mysqli_stmt_error($deleteStmt);

mysqli_stmt_close($deleteStmt);

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
        Ralat Padam | IR-KVKS
    </title>

</head>


<body>


<script>

    alert(
        "Gagal memadam aktiviti.\n\n<?= htmlspecialchars($error) ?>"
    );

    window.location.href = "index.php";

</script>


</body>

</html>