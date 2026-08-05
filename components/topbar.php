<?php
include(__DIR__ . "/../include/user_profile.php");
?>

<header class="topbar">

    <!-- LEFT -->
    <div class="topbar-left">

        <!-- Toggle Button -->
        <button id="toggleBtn" class="toggle-btn" title="Toggle Sidebar">
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- Search Box -->
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Cari sesuatu...">
        </div>

    </div>

    <!-- RIGHT -->
    <div class="topbar-right">

        <!-- Notification Button -->
        <button class="icon-btn" title="Notifikasi">
            <i class="fa-regular fa-bell"></i>
            <span class="badge">3</span>
        </button>

        <!-- Message Button -->
        <button class="icon-btn" title="Mesej">
            <i class="fa-regular fa-envelope"></i>
            <span class="badge">5</span>
        </button>

        <!-- Profile -->
        <div class="profile">
            <?php if(!empty($user['gambar'])): ?>
                <img src="<?= $baseUrl ?>uploads/profile/<?= htmlspecialchars($user['gambar']) ?>" alt="Profile" class="profile-img">
            <?php else: ?>
                <img src="<?= $baseUrl ?>asset/images/default-profile.png" alt="Profile" class="profile-img">
            <?php endif; ?>

<div class="profile-info">

    <h4>
        <?= htmlspecialchars($user['nama']); ?>
    </h4>


    <p>
        <?= ucfirst($_SESSION['role']); ?>
    </p>

</div>

            <i class="fa-solid fa-chevron-down"></i>
        </div>

    </div>

</header>
