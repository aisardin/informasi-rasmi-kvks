<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != "pensyarah"){
    header("Location: ../../pages/login.php");
    exit();
}

$baseUrl = "../../";

include("../../components/header.php");
include("../../components/sidebar.php");
?>