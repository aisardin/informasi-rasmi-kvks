<?php

session_start();

include("../components/config.php");


if(isset($_POST['upload'])){


    // ambil id pengguna
    $user_id = $_SESSION['user_id'];


    // semak fail gambar
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0){


        // nama asal gambar
        $gambar = $_FILES['gambar']['name'];

        // lokasi sementara
        $tmp = $_FILES['gambar']['tmp_name'];


        // extension gambar
        $ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));


        // format yang dibenarkan
        $jenis = ['jpg','jpeg','png'];


        if(in_array($ext,$jenis)){


            // nama baru
            $namaBaru = "profile_" . time() . "." . $ext;


            // folder simpan gambar
            $folderPath = "../uploads/profile/";


            // jika folder tiada, cipta folder
            if(!is_dir($folderPath)){
                mkdir($folderPath,0777,true);
            }


            // lokasi penuh gambar
            $folder = $folderPath . $namaBaru;



            // upload gambar
            if(move_uploaded_file($tmp,$folder)){


                // update database
                $sql = "UPDATE profiles
                        SET gambar='$namaBaru'
                        WHERE user_id='$user_id'";


                $result = mysqli_query($conn,$sql);



                if($result){

                    header("Location: ../pages/pelajar/dashboard.php");
                    exit();

                }
                else{

                    header("Location: profile.php");
                    exit();

                }


            }
            else{

                echo "Gagal upload gambar";

            }


        }
        else{


            echo "Format gambar hanya JPG, JPEG atau PNG";


        }



    }
    else{


        echo "Sila pilih gambar dahulu";


    }


}

?>