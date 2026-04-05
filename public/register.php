<?php
session_start();
require_once "../config/database.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $cek = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        mysqli_query($conn, "INSERT INTO users (name,email,password,role) 
                             VALUES ('$name','$email','$password','user')");
        $success = "Registrasi berhasil! Silakan login.";
        header("refresh:2;url=login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register | Oxsal Store</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    box-sizing:border-box;
    margin:0;
    padding:0;
    font-family:'Poppins',sans-serif;
}

body{
    background:#d9d4cc;
}

.container{
    display:flex;
    height:100vh;
}

/* ================= LEFT (FORM) ================= */
.left{
    width:55%;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    background:#c5ab8f;
    width:520px;
    padding:60px 50px;
    border-radius:18px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.card h2{
    text-align:center;
    font-size:36px;
    margin-bottom:15px;
}

.card p{
    text-align:center;
    margin-bottom:35px;
    font-size:15px;
}

.card a{
    color:#000;
    font-weight:600;
}

input{
    width:100%;
    padding:18px;
    margin-bottom:25px;
    border-radius:15px;
    border:1px solid #333;
    background:#f2f2f2;
    font-size:15px;
}

input:focus{
    outline:none;
    border:1px solid #000;
    background:#fff;
}

button{
    width:100%;
    padding:18px;
    background:#7b5a45;
    border:none;
    border-radius:20px;
    font-size:20px;
    font-weight:600;
    color:white;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#684836;
}

.error{
    background:#ffe0e0;
    color:#a10000;
    padding:12px;
    border-radius:10px;
    margin-bottom:20px;
    text-align:center;
    font-size:14px;
}

.success{
    background:#e2ffe2;
    color:#1a7f1a;
    padding:12px;
    border-radius:10px;
    margin-bottom:20px;
    text-align:center;
    font-size:14px;
}

/* ================= RIGHT (BRANDING) ================= */
.right{
    width:45%;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
}

.right h1{
    font-size:48px;
    letter-spacing:3px;
    color:#a67c00;
    margin-bottom:30px;
}

.right img{
    width:260px;
    margin-bottom:30px;
}

.tagline{
    font-size:22px;
    font-style:italic;
    color:#666;
    margin-bottom:15px;
}

.sub-tagline{
    font-size:18px;
    color:#ff5a00;
}

/* ================= RESPONSIVE ================= */
@media(max-width:900px){
    .container{
        flex-direction:column;
    }
    .left, .right{
        width:100%;
        height:auto;
        padding:40px 20px;
    }
}
</style>
</head>

<body>

<div class="container">

    <!-- LEFT : REGISTER FORM -->
    <div class="left">
        <div class="card">
            <h2>Daftar</h2>
            <p>Sudah punya akun Oxsal Store? <a href="login.php">Login disini</a></p>

            <?php if($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>

            <form method="post">
                <input type="text" name="name" placeholder="Masukkan nama anda" required>
                <input type="email" name="email" placeholder="Masukkan email anda" required>
                <input type="password" name="password" placeholder="Masukkan password anda" required>
                <button type="submit">Daftar</button>
            </form>
        </div>
    </div>

    <!-- RIGHT : BRANDING -->
    <div class="right">
        <h1>OXSAL STORE</h1>
        <img src="../assets/oxsal.png" alt="Logo Oxsal">
        <div class="tagline">Belanja Fashion Mudah dan Berkelas</div>
        <div class="sub-tagline">Buat akunmu dan mulai perjalanan stylish hari ini.</div>
    </div>

</div>

</body>
</html>