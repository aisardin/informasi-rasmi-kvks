<?php
session_start();

if(!isset($_SESSION['role']) ||
   $_SESSION['role'] != 'pelajar'){
    header("Location: ../login.php");
}
?>

<h1>Dashboard Pelajar</h1>

<p>Selamat Datang,
<?php echo $_SESSION['username']; ?>
</p>

<a href="../logout.php">Logout</a>