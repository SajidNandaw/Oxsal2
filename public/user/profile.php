<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('user');

$user_id = $_SESSION['id'];

$query = mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query) ?? [];

$nama   = $user['name'] ?? '-';
$email  = $user['email'] ?? '-';
$alamat = $user['alamat'] ?? 'Belum diisi';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Akun - Oxsal Store</title>

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
height:40px;
}

/* CONTAINER */
.container{
width:1100px;
margin:auto;
margin-top:30px;
}

/* BACK */
.back{
background:#d8c2a8;
padding:10px 22px;
border-radius:25px;
display:inline-block;
text-decoration:none;
color:black;
margin-bottom:30px;
font-weight:500;
}

/* TITLE */
.title{
font-size:30px;
font-weight:700;
margin-bottom:20px;
}

/* WRAPPER */
.wrapper{
display:flex;
gap:30px;
}

/* LEFT CARD */
.profile-card{
width:260px;
background:#fff;
border-radius:15px;
padding:20px;
box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

.profile-card h2{
font-size:28px;
margin-bottom:10px;
}

.avatar{
width:110px;
height:110px;
background:#c6a481;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
font-size:42px;
margin:15px auto;
}

/* 🔥 BUTTON FIX */
.btn-profile{
display:block;
width:100%;
margin-top:10px;
padding:8px;
border-radius:10px;
font-size:14px;
text-align:center;
text-decoration:none; /* 🔥 HILANGKAN UNDERLINE */
cursor:pointer;
transition:0.2s;
}

.btn-profile:hover{
opacity:0.9;
transform:scale(0.98);
}

.pass-btn{
background:#e6d5c3;
color:black;
}

.edit{
background:#c6a481;
color:white;
}

/* RIGHT CARD */
.info-box{
flex:1;
background:#f9f9f9;
border-radius:20px;
padding:20px;
border:1px solid #ddd;
}

.info-top{
display:flex;
justify-content:space-between;
align-items:center;
border-bottom:1px solid #ccc;
padding-bottom:10px;
margin-bottom:15px;
}

.logout{
background:#e6dcd2;
border:none;
padding:6px 18px;
border-radius:10px;
cursor:pointer;
}

.info p{
margin:12px 0;
}

/* ORDER */
.order{
margin-top:25px;
background:#fff;
padding:18px;
border-radius:30px;
display:flex;
justify-content:space-between;
box-shadow:0 2px 6px rgba(0,0,0,0.08);
}

/* FOOTER */
footer{
margin-top:50px;
background:#c6a481;
padding:40px 60px;
}

.footer-grid{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:30px;
}

.footer-title{
font-weight:700;
margin-bottom:10px;
}

.footer-brand{
display:flex;
align-items:center;
gap:10px;
margin-bottom:10px;
}

.footer-logo{
height:45px;
}

.footer-grid a{
text-decoration:none;
color:black;
}

.footer-bottom{
background:#7b5e4a;
color:white;
text-align:center;
padding:15px;
}

</style>
</head>

<body>

<header>
    <img src="../../assets/oxsal.png" class="logo-img">
    OXSAL STORE
</header>

<div class="container">

<a href="dashboard.php" class="back">← Kembali</a>

<div class="title">Profil Akun</div>

<div class="wrapper">

<div class="profile-card">
    <h2><?= $nama; ?></h2>

    <div class="avatar">
        <?= strtoupper(substr($nama,0,1)); ?>
    </div>

    <!-- 🔥 FIX DI SINI -->
    <a href="ubah_password.php" class="btn-profile pass-btn">Ubah Password</a>
    <a href="edit_profil.php" class="btn-profile edit">Edit</a>
</div>

<div class="info-box">

    <div class="info-top">
        <h3>Halo, <?= $nama; ?></h3>

        <a href="../logout.php">
            <button class="logout">Logout</button>
        </a>
    </div>

    <div class="info">
        <p><b>Nama :</b> <?= $nama; ?></p>
        <p><b>Email :</b> <?= $email; ?></p>
        <p><b>Alamat :</b> <?= $alamat; ?></p>
    </div>

</div>

</div>

<a href="riwayat_pesanan.php" style="text-decoration:none;color:black;">
<div class="order">
<span>⏱ Riwayat Pesanan</span>
<span>›</span>
</div>
</a>

</div>

<footer>

<div class="footer-grid">

<div>
<div class="footer-title">Layanan</div>
<p><a href="#">Tentang Kami</a></p>
<p><a href="#">Produk</a></p>
<p><a href="#">Keranjang</a></p>
</div>

<div>
<div class="footer-title">Hubungi Kami</div>
<p>oxsalstorekc@gmail.com</p>
<p>0815-3465-8489</p>
<p>Indonesia</p>
</div>

<div>
<div class="footer-title">Bantuan</div>
<p><a href="cara_belanja.php">Cara Belanja</a></p>
<p><a href="metode_pembayaran.php">Metode Pembayaran</a></p>
<p><a href="pengiriman.php">Pengiriman</a></p>
<p><a href="syarat_ketentuan.php">Syarat & Ketentuan</a></p>
</div>

<div>
<div class="footer-brand">
<img src="../../assets/oxsal.png" class="footer-logo">
<div class="footer-title">OXSAL STORE</div>
</div>
<p>Oxsal Store hadir sebagai ruang belanja fashion modern dan terpercaya.</p>
</div>

</div>

</footer>

<div class="footer-bottom">
© Oxsal Store 2026. All Rights Reserved
</div>

</body>
</html>