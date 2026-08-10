<!-- =====================================
     ADMIN SIDEBAR
====================================== -->

<aside class="admin-sidebar" id="adminSidebar">

    <!-- LOGO -->
    <div class="admin-sidebar-logo">

        <img
            src="/dashboard/IRKVKS/asset/images/logo.png"
            alt="IR-KVKS"
        >

        <div class="admin-logo-text">

            <strong>IR-KVKS</strong>

            <span>Panel Admin</span>

        </div>

    </div>


    <!-- NAVIGATION -->
    <nav class="admin-sidebar-nav">

        <!-- UTAMA -->
        <div class="admin-menu-title">
            UTAMA
        </div>


        <a
            href="/dashboard/IRKVKS/pages/admin/dashboard.php"
            class="admin-nav-link"
        >

            <i class="fa-solid fa-house"></i>

            <span>Dashboard</span>

        </a>


        <!-- PENGURUSAN -->
        <div class="admin-menu-title">
            PENGURUSAN
        </div>


        <a
            href="/dashboard/IRKVKS/pages/admin/pengumuman/"
            class="admin-nav-link"
        >

            <i class="fa-solid fa-bullhorn"></i>

            <span>Pengumuman</span>

        </a>


        <a
            href="/dashboard/IRKVKS/pages/admin/akademik/"
            class="admin-nav-link"
        >

            <i class="fa-solid fa-book-open"></i>

            <span>Akademik</span>

        </a>


        <a
            href="/dashboard/IRKVKS/pages/admin/aktiviti/"
            class="admin-nav-link"
        >

            <i class="fa-solid fa-calendar-days"></i>

            <span>Aktiviti</span>

        </a>


        <a
            href="/dashboard/IRKVKS/pages/admin/pengguna/"
            class="admin-nav-link"
        >

            <i class="fa-solid fa-users"></i>

            <span>Pengguna</span>

        </a>


        <!-- AKAUN -->
        <div class="admin-menu-title">
            AKAUN
        </div>


        <a
            href="/dashboard/IRKVKS/pages/admin/profil.php"
            class="admin-nav-link"
        >

            <i class="fa-solid fa-user"></i>

            <span>Profil Saya</span>

        </a>


        <a
            href="/dashboard/IRKVKS/pages/admin/tetapan.php"
            class="admin-nav-link"
        >

            <i class="fa-solid fa-gear"></i>

            <span>Tetapan</span>

        </a>

    </nav>


    <!-- LOGOUT -->
    <div class="admin-sidebar-bottom">

        <a
            href="/dashboard/IRKVKS/auth/logout.php"
            class="admin-logout-link"
            onclick="return confirm('Adakah anda pasti mahu log keluar?');"
        >

            <i class="fa-solid fa-right-from-bracket"></i>

            <span>Log Keluar</span>

        </a>

    </div>

</aside>