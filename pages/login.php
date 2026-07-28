<?php
session_start();

if (isset($_SESSION['user_id'])) {

  if ($_SESSION['role'] == "pelajar") {
    header("Location: dashboard-pelajar.php");
    exit();
  }

  if ($_SESSION['role'] == "pensyarah") {
    header("Location: dashboard-pensyarah.php");
    exit();
  }

}

include("../components/config.php");
include("../components/header.php");
?>

<link rel="stylesheet" href="../asset/css/login.css">
<style>
  /* ==========================
    ROLE SELECTED
========================== */

.role-card.selected div{

    border-color:#0d6efd;

    background:#eff6ff;

    transform:translateY(-5px);

    box-shadow:0 15px 35px rgba(13,110,253,.15);

}

/* Hover */

.role-card div{

    transition:.3s;

}

.role-card:hover div{

    transform:translateY(-5px);

    box-shadow:0 15px 30px rgba(0,0,0,.08);

}
</style>
<body>

  <div class="login-container">

    <!-- ===========================
            LEFT PANEL
    ============================ -->

    <div class="login-left">

      <div class="overlay"></div>

      <div class="left-content">

        <img src="../asset/images/logo.png" class="logo">

        <h1>IR-KVKS</h1>

        <h3>
          Sistem Perkongsian Maklumat
          <br>
          Rasmi Guru & Pelajar
        </h3>

        <div class="line"></div>

        <p>
          Platform rasmi untuk perkongsian maklumat,
          bahan pembelajaran, pengumuman dan komunikasi
          antara guru dan pelajar Kolej Vokasional.
        </p>

      </div>

      <div class="quote-box">

        <i class="fa-solid fa-quote-left"></i>

        <p>

          Pendidikan adalah senjata paling ampuh
          yang boleh anda gunakan untuk mengubah dunia.

        </p>

        <span>— Nelson Mandela</span>

      </div>

    </div>

    <!-- ===========================
            RIGHT PANEL
    ============================ -->

    <div class="login-right">

      <div class="login-card">

        <h2>Log Masuk ke Akaun Anda</h2>

        <p class="subtitle">
          Sila masukkan maklumat akaun anda.
        </p>

        <form action="../auth/login_process.php" method="POST">

          <!-- Username -->

          <div class="input-group">

            <label>ID Pengguna</label>

            <div class="input-box">

              <i class="fa-regular fa-user"></i>

              <input type="text" name="username" placeholder="Masukkan ID Pengguna" required>

            </div>

          </div>

          <!-- Password -->

          <div class="input-group">

            <label>Kata Laluan</label>

            <div class="input-box">

              <i class="fa-solid fa-lock"></i>

              <input type="password" id="password" name="password" placeholder="Masukkan Kata Laluan" required>

              <i class="fa-regular fa-eye-slash toggle-password" id="togglePassword">
              </i>

            </div>

          </div>

          <!-- Remember -->

          <div class="remember">

            <label>

              <input type="checkbox">

              Ingat Saya

            </label>

            <a href="#">
              Lupa Kata Laluan?
            </a>

          </div>

          <!-- ROLE -->

          <h4>Log Masuk Sebagai</h4>

          <div class="role-container">

            <label class="role-card">

              <input type="radio" name="role" value="pensyarah" required>

              <div>

                <i class="fa-solid fa-user-tie"></i>

                <h5>Pensyarah</h5>

                <span>Log masuk sebagai Pensyarah</span>

              </div>

            </label>

            <label class="role-card">

              <input type="radio" name="role" value="pelajar" required>

              <div>

                <i class="fa-solid fa-user-graduate"></i>

                <h5>Pelajar</h5>

                <span>Log masuk sebagai Pelajar</span>

              </div>

            </label>

          </div>

          <!-- BUTTON -->

          <button type="submit">

            <i class="fa-solid fa-right-to-bracket"></i>

            Log Masuk

          </button>

        </form>

        <div class="copyright">

          © 2026 IR-KVKS. Hak Cipta Terpelihara.

        </div>

      </div>

    </div>

  </div>

  <script src="../asset/js/login.js"></script>

</body>

</html>