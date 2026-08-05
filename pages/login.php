<?php

session_start();

if (isset($_SESSION['user_id'])) {

    switch ($_SESSION['role']) {

        case "admin":
            header("Location: admin/dashboard.php");
            exit();

        case "pensyarah":
            header("Location: pensyarah/dashboard.php");
            exit();

        case "pelajar":
            header("Location: pelajar/dashboard.php");
            exit();
    }
}

$baseUrl = "/dashboard/IRKVKS/";

include("../components/header.php");


?>
    <link rel="stylesheet" href="<?= $baseUrl ?>asset/css/login.css">

<body>

<div class="login-container">

    <div class="login-left">

        <div class="overlay"></div>

        <div class="left-content">

            <img src="<?= $baseUrl ?>asset/images/logo.png" class="logo">

            <h1>IR-KVKS</h1>

            <h3>Info Rasmi Kolej Vokasional Kuala Selangor</h3>

            <div class="line"></div>

            <p>
                Platform rasmi pengurusan maklumat,
                pengumuman dan tugasan pelajar.
            </p>

        </div>

    </div>

    <div class="login-right">

        <div class="login-card">

            <h2>Log Masuk</h2>

            <p class="subtitle">
                Sila log masuk menggunakan email.
            </p>

            <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='error'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
            ?>

            <form action="<?= $baseUrl ?>auth/login_process.php" method="POST">

                <div class="input-group">

                    <label>Email</label>

                    <div class="input-box">

                        <i class="fa-solid fa-envelope"></i>

                        <input
                            type="email"
                            name="email"
                            placeholder="Masukkan email"
                            required>

                    </div>

                </div>

                <div class="input-group">

                    <label>Password</label>

                    <div class="input-box">

                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan password"
                            required>

                    </div>

                </div>

                <button type="submit" name="login">

                    <i class="fa-solid fa-right-to-bracket"></i>

                    Log Masuk

                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>