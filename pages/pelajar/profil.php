<?php
session_start();

// =====================================
// SEMAK LOGIN
// =====================================
if (!isset($_SESSION['role']) || $_SESSION['role'] != "pelajar") {
    header("Location: ../../pages/login.php");
    exit();
}

$baseUrl = "../../";

// =====================================
// DATABASE
// =====================================
include(__DIR__ . "/../../components/config.php");

// =====================================
// CSS KHUSUS PAGE
// =====================================
$pageCss = "profil.css";

// =====================================
// DAPATKAN ID PENGGUNA
// =====================================
$userId = $_SESSION['user_id'];

// =====================================
// DAPATKAN MAKLUMAT PENGGUNA
// =====================================
$sqlUser = "
    SELECT
        id,
        nama,
        email,
        role,
        gambar
    FROM users
    WHERE id = ?
    LIMIT 1
";

$stmtUser = mysqli_prepare($conn, $sqlUser);

if (!$stmtUser) {
    die("Ralat SQL: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmtUser, "i", $userId);
mysqli_stmt_execute($stmtUser);

$resultUser = mysqli_stmt_get_result($stmtUser);

$user = mysqli_fetch_assoc($resultUser);

mysqli_stmt_close($stmtUser);

// =====================================
// JIKA USER TIDAK DIJUMPAI
// =====================================
if (!$user) {
    session_destroy();

    header("Location: ../../pages/login.php");
    exit();
}

// =====================================
// GAMBAR PROFIL
// =====================================
if (!empty($user['gambar'])) {

    $profileImage = $baseUrl . "uploads/profile/" . $user['gambar'];

} else {

    $profileImage = $baseUrl . "asset/images/default-profile.png";

}


// =====================================
// HEADER
// =====================================
$pageCss = "profil.css";
include("../../components/header.php");

// =====================================
// SIDEBAR
// =====================================
include("../../components/sidebar.php");
?>

<body>

<div class="main-content" id="mainContent">

    <!-- =====================================
         TOPBAR
    ====================================== -->

    <?php include("../../components/topbar.php"); ?>





    <!-- =====================================
         PROFILE CONTENT
    ====================================== -->

    <div class="profile-content">


        <!-- =================================
             PROFILE CARD
        ================================== -->

        <div class="profile-card">


            <!-- PROFILE HEADER -->

            <div class="profile-header">


                <div class="profile-image-wrapper">

                    <img
                        src="<?= htmlspecialchars($profileImage) ?>"
                        alt="Gambar Profil"
                        class="profile-image"
                    >

                </div>


                <div class="profile-heading">

                    <h2>
                        <?= htmlspecialchars($user['nama']) ?>
                    </h2>

                    <p>
                        <?= htmlspecialchars($user['email']) ?>
                    </p>


                    <span class="role-badge">

                        <i class="fa-solid fa-user-graduate"></i>

                        Pelajar

                    </span>

                </div>

            </div>


            <!-- =================================
                 PROFILE INFORMATION
            ================================== -->

            <div class="profile-section">

                <div class="section-title">

                    <i class="fa-solid fa-circle-info"></i>

                    <h3>Maklumat Akaun</h3>

                </div>


                <div class="profile-info-grid">


                    <!-- NAMA -->

                    <div class="profile-info-item">

                        <span class="info-label">
                            Nama
                        </span>

                        <strong>
                            <?= htmlspecialchars($user['nama']) ?>
                        </strong>

                    </div>


                    <!-- EMAIL -->

                    <div class="profile-info-item">

                        <span class="info-label">
                            Email
                        </span>

                        <strong>
                            <?= htmlspecialchars($user['email']) ?>
                        </strong>

                    </div>


                    <!-- ROLE -->

                    <div class="profile-info-item">

                        <span class="info-label">
                            Peranan
                        </span>

                        <strong>
                            Pelajar
                        </strong>

                    </div>


                    <!-- ID -->

                    <div class="profile-info-item">

                        <span class="info-label">
                            ID Pengguna
                        </span>

                        <strong>
                            #<?= htmlspecialchars($user['id']) ?>
                        </strong>

                    </div>


                </div>

            </div>

        </div>


        <!-- =================================
             ACCOUNT STATUS
        ================================== -->

        <div class="account-card">

            <div class="account-icon">

                <i class="fa-solid fa-shield-halved"></i>

            </div>


            <div class="account-info">

                <h3>Akaun Aktif</h3>

                <p>
                    Akaun anda sedang aktif dan boleh digunakan
                    untuk mengakses sistem IR-KVKS.
                </p>

            </div>


            <span class="active-badge">

                Aktif

            </span>

        </div>


    </div>

</div>


<div class="sidebar-overlay"></div>

</body>

</html>