<?php
session_start();

/*
|--------------------------------------------------------------------------
| IR-KVKS Logout System
|--------------------------------------------------------------------------
| 1. Mulakan session
| 2. Kosongkan semua data session
| 3. Musnahkan session
| 4. Kembali ke Landing Page
|--------------------------------------------------------------------------
*/

// Kosongkan semua data session
$_SESSION = [];

// Padam cookie session (lebih selamat)
if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );

}

// Tamatkan session
session_destroy();

// Redirect ke Landing Page
header("Location: ../index.php");
exit();
?>