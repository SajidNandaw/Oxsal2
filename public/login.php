<?php
session_start();
require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Ambil input
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    // Query user
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");

    if ($query && mysqli_num_rows($query) > 0) {

        $user = mysqli_fetch_assoc($query);

        // Verifikasi password
        if (password_verify($password, $user["password"])) {

            $_SESSION["login"] = true;
            $_SESSION["id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];

            // Redirect sesuai role
            if ($user["role"] === "admin") {
                header("Location: admin/dashboard.php");
            } elseif ($user["role"] === "petugas") {
                header("Location: petugas/dashboard.php");
            } else {
                header("Location: user/dashboard.php");
            }
            exit;

        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "Akun tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login | Oxsal Store</title>

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

/* LEFT */
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

/* RIGHT */
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

/* RESPONSIVE */
@media(max-width:900px){
    .container{
        flex-direction:column;
    }
    .left, .right{
        width:100%;
        padding:40px 20px;
    }
}
</style>
</head>

<body>

<div class="container">

    <!-- LEFT -->
    <div class="left">
        <div class="card">
            <h2>Login</h2>
            <p>Belum punya akun Oxsal Store? <a href="register.php">Daftar disini</a></p>

            <?php if($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <input type="email" name="email" placeholder="Masukkan email anda" required>
                <input type="password" name="password" placeholder="Masukkan password anda" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right">
        <h1>OXSAL STORE</h1>
        <img src="../assets/oxsal.png" alt="Logo Oxsal">
        <div class="tagline">Belanja Fashion Mudah dan Berkelas</div>
        <div class="sub-tagline">Mulai langkah barumu bersama Oxsal Store hari ini.</div>
    </div>

</div>

</body>
</html>