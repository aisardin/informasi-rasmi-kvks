<!DOCTYPE html>
<html lang="en">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Rasmi KVks</title>
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>asset/css/sidebar.css">
    <link rel="stylesheet" href="<?= $baseUrl?>asset/css/topbar.css">
    <link rel="stylesheet" href="<?= $baseUrl?>asset/css/dashboard.css">
<?php if(isset($pageCss)){ ?>
<link rel="stylesheet" href="<?= $baseUrl ?>asset/css/<?= $pageCss ?>">
<?php } ?>

    <script src="<?= $baseUrl ?>asset/js/sidebar.js"></script>
    <script src="<?= $baseUrl ?>asset/js/login.js"></script>

    <style>
html, body{
    scrollbar-width:none;
    -ms-overflow-style:none;
    scroll-behavior:smooth;

}

body::-webkit-scrollbar{
    display:none; /* Chrome */
}


    </style>
</head>
<script>


document.addEventListener('DOMContentLoaded', function(){


    var calendarEl = document.getElementById('calendar');


    var calendar = new FullCalendar.Calendar(calendarEl, {


        initialView:'dayGridMonth',


        height:450,


        headerToolbar:{


            left:'prev,next',

            center:'title',

            right:''


        },


        events: <?= $calendarData ?>,


        eventColor:'#2563eb'


    });



    calendar.render();


});


</script>
<body>