<?php

session_start();

include("../components/config.php");


// semak submit form
if(isset($_POST['login'])){


    $email = $_POST['email'];
    $password = $_POST['password'];



    // cari user berdasarkan email
    $sql = "SELECT * FROM users 
            WHERE email='$email'
            LIMIT 1";


    $result = mysqli_query($conn,$sql);



    // jika email wujud
    if(mysqli_num_rows($result) > 0){


        $user = mysqli_fetch_assoc($result);
        



        // semak password
        if(password_verify($password,$user['password'])){



            // simpan session

            $_SESSION['user_id'] = $user['id'];

            $_SESSION['nama'] = $user['nama'];

            $_SESSION['email'] = $user['email'];

            $_SESSION['role'] = $user['role'];

            $_SESSION['gambar'] = $user['gambar'];



            // redirect ikut role

            if($user['role']=="admin"){


                header("Location: ../pages/admin/dashboard.php");


            }


            elseif($user['role']=="pensyarah"){


                header("Location: ../pages/pensyarah/dashboard.php");


            }


            elseif($user['role']=="pelajar"){


                header("Location: ../pages/pelajar/dashboard.php");


            }


            exit();


        }

        else{


            $_SESSION['error']="Password salah";


            header("Location: ../pages/login.php");

            exit();


        }



    }

    else{


        $_SESSION['error']="Email tidak dijumpai";


        header("Location: ../pages/login.php");

        exit();


    }



}

?>