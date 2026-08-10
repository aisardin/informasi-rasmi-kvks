<!-- =====================================
     ADMIN TOPBAR
====================================== -->

<header class="admin-topbar">

    <!-- LEFT -->
    <div class="admin-topbar-left">

<button
    type="button"
    class="admin-toggle-btn"
    id="adminToggleBtn"
>
    <i class="fa-solid fa-bars"></i>
</button>


        <!-- SEARCH -->
        <div class="admin-search">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                id="adminSearch"
                placeholder="Cari sesuatu..."
                autocomplete="off"
            >

        </div>

    </div>


    <!-- RIGHT -->
    <div class="admin-topbar-right">


        <!-- NOTIFICATION -->
        <button
            type="button"
            class="admin-topbar-icon"
            title="Notifikasi"
        >

            <i class="fa-regular fa-bell"></i>

            <span class="notification-badge">
                3
            </span>

        </button>


        <!-- MESSAGE -->
        <button
            type="button"
            class="admin-topbar-icon"
            title="Mesej"
        >

            <i class="fa-regular fa-envelope"></i>

            <span class="notification-badge">
                5
            </span>

        </button>


        <!-- ADMIN PROFILE -->
        <div class="admin-profile">

            <div class="admin-profile-image">

                <?php
                $adminImage = !empty($user['gambar'])
                    ? "/dashboard/IRKVKS/uploads/profile/" . htmlspecialchars($user['gambar'])
                    : "/dashboard/IRKVKS/asset/images/default-profile.png";
                ?>

                <img
                    src="<?= $adminImage ?>"
                    alt="Profil Admin"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                >

                <div class="admin-profile-placeholder">
                    <i class="fa-solid fa-user"></i>
                </div>

            </div>


            <div class="admin-profile-info">

                <strong>
                    <?= htmlspecialchars($user['nama'] ?? 'Admin') ?>
                </strong>

                <span>
                    Pentadbir
                </span>

            </div>


            <i class="fa-solid fa-chevron-down admin-profile-arrow"></i>

        </div>

    </div>

</header>


