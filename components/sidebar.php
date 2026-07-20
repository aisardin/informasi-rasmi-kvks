<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Font Awesome -->
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


<div class="sidebar" id="sidebar">

    <!-- Logo -->
    <div class="logo-section">

        <div class="logo">

            <img src="../../asset/images/logo kv.png" alt="Logo" class="toggle">

            <div class="logo-text">
                <h2>IR-KVKS</h2>
                <p>Sistem Perkongsian Maklumat</p>
            </div>

        </div>

    </div>

    <!-- Menu -->
    <div class="menu">

<?php

if($_SESSION['role']=="pelajar"){

?>

<a href="dashboard-pelajar.php"
class="<?=($currentPage=="dashboard-pelajar.php")?'active':'';?>">

<i class="fa-solid fa-house"></i>

<span>Dashboard</span>

</a>

<a href="pengumuman.php">

<i class="fa-solid fa-bullhorn"></i>

<span>Pengumuman</span>

</a>

<a href="jadual.php">

<i class="fa-solid fa-calendar-days"></i>

<span>Jadual & Takwim</span>

</a>

<a href="bahan.php">

<i class="fa-solid fa-book-open"></i>

<span>Bahan Pembelajaran</span>

</a>

<a href="tugasan.php">

<i class="fa-solid fa-file-circle-check"></i>

<span>Tugasan Saya</span>

</a>

<a href="komunikasi.php">

<i class="fa-solid fa-comments"></i>

<span>Komunikasi</span>

</a>

<a href="aktiviti.php">

<i class="fa-solid fa-calendar-check"></i>

<span>Aktiviti</span>

</a>

<a href="profil.php">

<i class="fa-solid fa-user"></i>

<span>Profil</span>

</a>

<a href="tetapan.php">

<i class="fa-solid fa-gear"></i>

<span>Tetapan</span>

</a>

<?php

}else{

?>

<a href="dashboard-pensyarah.php"
class="<?=($currentPage=="dashboard-pensyarah.php")?'active':'';?>">

<i class="fa-solid fa-house"></i>

<span>Dashboard</span>

</a>

<a href="pengumuman.php">

<i class="fa-solid fa-bullhorn"></i>

<span>Pengurusan Pengumuman</span>

</a>

<a href="bahan.php">

<i class="fa-solid fa-book-open"></i>

<span>Bahan Pembelajaran</span>

</a>

<a href="pelajar.php">

<i class="fa-solid fa-user-graduate"></i>

<span>Pengurusan Pelajar</span>

</a>

<a href="tugasan.php">

<i class="fa-solid fa-file-circle-check"></i>

<span>Tugasan</span>

</a>

<a href="jadual.php">

<i class="fa-solid fa-calendar-days"></i>

<span>Jadual</span>

</a>

<a href="komunikasi.php">

<i class="fa-solid fa-comments"></i>

<span>Komunikasi</span>

</a>

<a href="profil.php">

<i class="fa-solid fa-user"></i>

<span>Profil</span>

</a>

<a href="tetapan.php">

<i class="fa-solid fa-gear"></i>

<span>Tetapan</span>

</a>

<?php

}

?>

    </div>

    <!-- Logout -->

    <div class="logout" onclick="return confirm('anda pasti ingin keluar?')">

        <a href="../../auth/logout.php">

            <i class="fa-solid fa-right-from-bracket"></i>

            <span>Log Keluar</span>

        </a>

    </div>

</div>