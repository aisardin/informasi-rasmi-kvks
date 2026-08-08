<?php
session_start();

// ==============================
// SEMAK LOGIN
// ==============================
if (!isset($_SESSION['role']) || $_SESSION['role'] != "pelajar") {
    header("Location: ../../pages/login.php");
    exit();
}

$baseUrl = "../../";

// ==============================
// DATABASE
// ==============================
include(__DIR__ . "/../../components/config.php");

// ==============================
// MAKLUMAT PENGGUNA
// ==============================
$userId = $_SESSION['user_id'];

$sqlUser = "SELECT * FROM users WHERE id=? LIMIT 1";

$stmtUser = mysqli_prepare($conn,$sqlUser);
mysqli_stmt_bind_param($stmtUser,"i",$userId);
mysqli_stmt_execute($stmtUser);

$resultUser = mysqli_stmt_get_result($stmtUser);

$user = mysqli_fetch_assoc($resultUser);

mysqli_stmt_close($stmtUser);

// ==============================
// DAPATKAN SENARAI KELAS
// ==============================

$query = "

SELECT

k.id,
k.nama_kelas,
k.kod_subjek,
k.semester,

u.nama AS pensyarah,

(
SELECT COUNT(*)
FROM bahan b
WHERE b.kelas_id=k.id
) AS jumlah_bahan,

(
SELECT COUNT(*)
FROM tugasan t
WHERE t.kelas_id=k.id
) AS jumlah_tugasan,

(
SELECT COUNT(*)
FROM kelas_pelajar kp2
WHERE kp2.kelas_id=k.id
) AS jumlah_pelajar

FROM kelas k

INNER JOIN kelas_pelajar kp
ON kp.kelas_id=k.id

INNER JOIN users u
ON u.id=k.pensyarah_id

WHERE kp.pelajar_id=?

ORDER BY k.nama_kelas ASC

";

$stmt = mysqli_prepare($conn,$query);

mysqli_stmt_bind_param($stmt,"i",$userId);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// Header
include("../../components/header.php");

// Sidebar
include("../../components/sidebar.php");
?>

<body>

<div class="main-content" id="mainContent">

<?php include("../../components/topbar.php"); ?>


<!-- ========================= -->
<!-- PAGE HEADER -->
<!-- ========================= -->

<div class="page-header">

    <div>

        <h1>Akademik</h1>

        <p>
            Senarai kelas yang anda sertai.
        </p>

    </div>

</div>


<!-- ========================= -->
<!-- CONTENT -->
<!-- ========================= -->

<div class="dashboard-content">


<div class="search-filter">

<input
type="text"
id="searchClass"
placeholder="Cari subjek..."
>

</div>



<div class="class-grid">


<?php if(mysqli_num_rows($result)>0): ?>


<?php while($kelas=mysqli_fetch_assoc($result)): ?>


<div class="class-card">

<div class="class-top">

<div class="class-icon">

<i class="fa-solid fa-book"></i>

</div>

<div>

<h3>

<?= htmlspecialchars($kelas['nama_kelas']) ?>

</h3>

<p>

<?= htmlspecialchars($kelas['kod_subjek']) ?>

</p>

</div>

</div>


<div class="class-info">

<div>

<label>Pensyarah</label>

<span>

<?= htmlspecialchars($kelas['pensyarah']) ?>

</span>

</div>


<div>

<label>Semester</label>

<span>

<?= $kelas['semester'] ?>

</span>

</div>

</div>



<div class="class-stats">

<div>

<h4>

<?= $kelas['jumlah_bahan'] ?>

</h4>

<p>Bahan</p>

</div>

<div>

<h4>

<?= $kelas['jumlah_tugasan'] ?>

</h4>

<p>Tugasan</p>

</div>

<div>

<h4>

<?= $kelas['jumlah_pelajar'] ?>

</h4>

<p>Pelajar</p>

</div>

</div>



<a

href="kelas-detail.php?id=<?= $kelas['id'] ?>"

class="enter-btn"

>

<i class="fa-solid fa-arrow-right"></i>

Masuk Kelas

</a>


</div>


<?php endwhile; ?>


<?php else: ?>


<div class="empty-card">

<i class="fa-solid fa-book-open"></i>

<h2>

Tiada kelas dijumpai

</h2>

<p>

Anda belum didaftarkan ke mana-mana kelas.

</p>

</div>


<?php endif; ?>


</div>

</div>

</div>


<div class="sidebar-overlay"></div>


<script>

const search=document.getElementById("searchClass");

search.addEventListener("keyup",function(){

let value=this.value.toLowerCase();

let cards=document.querySelectorAll(".class-card");

cards.forEach(card=>{

let text=card.innerText.toLowerCase();

card.style.display=text.includes(value) ? "block":"none";

});

});

</script>

</body>
</html>