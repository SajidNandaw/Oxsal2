<?php
ob_start(); // 🔥 WAJIB biar header aman

require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('user');

$user_id = $_SESSION['id'];

if(isset($_POST['update'])){

$password_lama = $_POST['password_lama'];
$password_baru_input = $_POST['password_baru'];
$password_baru = password_hash($password_baru_input, PASSWORD_DEFAULT);

$query = mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);

if($user && password_verify($password_lama,$user['password'])){

mysqli_query($conn,"
UPDATE users 
SET password='$password_baru'
WHERE id='$user_id'
");

// 🔥 REDIRECT SUPER AMAN
header("Location: profile.php");
echo "<script>window.location.href='profile.php';</script>";
exit;

}else{
$error = "Password lama salah!";
}

}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ubah Password - Oxsal Store</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#efe7de;
}

/* NAVBAR */
header{
background:#7b5e4a;
padding:15px 50px;
display:flex;
align-items:center;
gap:12px;
color:white;
font-weight:600;
font-size:20px;
}

.logo-img{
width:35px;
height:35px;
border-radius:50%;
object-fit:cover;
}

/* CONTAINER */
.container{
width:400px;
margin:auto;
margin-top:80px;
}

/* BACK */
.back{
display:inline-block;
margin-bottom:20px;
text-decoration:none;
color:black;
background:#d8c2a8;
padding:8px 18px;
border-radius:20px;
font-weight:500;
}

/* CARD */
.card{
background:#ffffff;
padding:30px;
border-radius:20px;
box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

/* TITLE */
.title{
font-size:22px;
font-weight:600;
margin-bottom:20px;
text-align:center;
}

/* ERROR */
.error{
background:#ffd6d6;
padding:10px;
border-radius:10px;
margin-bottom:15px;
text-align:center;
}

/* FORM */
label{
font-size:14px;
font-weight:500;
}

input{
width:100%;
padding:12px;
margin-top:6px;
margin-bottom:15px;
border-radius:10px;
border:1px solid #ddd;
outline:none;
background:#f9f9f9;
}

input:focus{
border:1px solid #c6a481;
}

/* BUTTON */
button{
width:100%;
padding:12px;
background:#c6a481;
color:white;
border:none;
border-radius:12px;
cursor:pointer;
font-weight:600;
}

button:hover{
opacity:0.9;
}

/* FOOTER */
.footer-bottom{
background:#7b5e4a;
color:white;
text-align:center;
padding:15px;
margin-top:100px;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<header>
    <img src="../../assets/oxsal.png" class="logo-img">
    OXSAL STORE
</header>

<div class="container">

<a href="profile.php" class="back">← Kembali</a>

<div class="card">

<div class="title">Ubah Password</div>

<?php if(isset($error)): ?>
<div class="error"><?= $error; ?></div>
<?php endif; ?>

<form method="POST">

<label>Password Lama</label>
<input type="password" name="password_lama" required>

<label>Password Baru</label>
<input type="password" name="password_baru" required>

<button name="update">Simpan Password</button>

</form>

</div>

</div>

<div class="footer-bottom">
© Oxsal Store 2026. All Rights Reserved
</div>

</body>
</html>

<?php ob_end_flush(); ?>