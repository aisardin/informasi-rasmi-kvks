<?php
include("../components/config.php");
include("../components/head.php");
?>
<style>
*{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Poppins,sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#f4f4f4;
        }

        .card{
            width:350px;
            background:white;
            padding:30px;
            border-radius:15px;
            box-shadow:0 0 20px rgba(0,0,0,.1);
        }

        .card h2{
            text-align:center;
            margin-bottom:20px;
        }

        input,
        select{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:8px;
        }

        button{
            width:100%;
            padding:12px;
            border:none;
            background:#007bff;
            color:white;
            border-radius:8px;
            cursor:pointer;
        }

        button:hover{
            background:#0056b3;
        }
</style>

<body>

<div class="card">

    <h2>Login IR-KVKS</h2>

    <form action="../auth/login_process.php" method="POST">

        <input
            type="text"
            name="username"
            placeholder="Username"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <select name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="pelajar">Pelajar</option>
            <option value="pensyarah">Pensyarah</option>
        </select>

        <button type="submit">
            Login
        </button>

    </form>

</div>

</body>