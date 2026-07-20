<?php

session_start();
include "../components/config.php";

$username = mysqli_real_escape_string(
    $conn,
    $_POST['username']
);

$password = md5($_POST['password']);

$role = mysqli_real_escape_string(
    $conn,
    $_POST['role']
);

$sql = "SELECT * FROM users
        WHERE username='$username'
        AND password='$password'
        AND role='$role'";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result) == 1){

    $user = mysqli_fetch_assoc($result);

    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    if($user['role'] == 'pelajar'){
        header("Location: ../pages/pelajar/dashboard.php");
    }
    else{
        header("Location: ../pages/pensyarah/dashboard.php");
    }

    exit();

}
else{

    echo "
        <script>
            alert('Login Gagal');
            window.location='../pages/login.php';
        </script>
    ";

}

?>