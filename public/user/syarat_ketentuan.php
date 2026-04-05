<?php
require_once "../../middleware/auth.php";
require_once __DIR__ . "/../../config/database.php";

checkRole('user');

$user_id = $_SESSION['id'] ?? 0;

$qCart = mysqli_query($conn,"SELECT SUM(jumlah) as total_item FROM keranjang WHERE user_id='$user_id'");
$dataCart = mysqli_fetch_assoc($qCart);
$totalCart = $dataCart['total_item'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Syarat & Kebijakan - Oxsal Store</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
background:#e9e3db;
display:flex;
flex-direction:column;
min-height:100vh;
}

/* HEADER */
header{
background:#6b4b3e;
padding:15px 60px;
display:flex;
align-items:center;
justify-content:space-between;
color:white;
}

.logo{
display:flex;
align-items:center;
gap:10px;
font-size:20px;
font-weight:600;
text-decoration:none;
color:white;
}

.logo img{width:40px;}

.search-box{
width:450px;
background:#d9d4cd;
border-radius:25px;
padding:8px 20px;
}

.search-box input{
width:100%;
border:none;
background:transparent;
outline:none;
}

.header-icons{
display:flex;
gap:20px;
font-size:20px;
}

.cart-icon{
position:relative;
text-decoration:none;
color:white;
}

.cart-badge{
position:absolute;
top:-8px;
right:-10px;
background:red;
color:white;
font-size:11px;
padding:2px 6px;
border-radius:50%;
}

/* CONTENT */
.container{
padding:40px 80px;
flex:1;
}

.breadcrumb{
margin-bottom:20px;
font-size:14px;
}

.breadcrumb a{
text-decoration:none;
color:#6b4b3e;
font-weight:500;
}

.title{
font-size:32px;
font-weight:600;
margin-bottom:20px;
}

.content-text{
max-width:900px;
line-height:1.8;
color:#333;
}

.content-text ul{
margin-left:20px;
}

footer{
background:#6b4b3e;
color:white;
text-align:center;
padding:15px;
}

</style>
</head>

<body>

<header>
<a href="dashboard.php" class="logo">
<img src="../../assets/kiostore2.png">
OXSAL STORE
</a>

<div class="search-box">
<input type="text" placeholder="Cari di Oxsal Store">
</div>

<div class="header-icons">
<a href="keranjang-produk.php" class="cart-icon">🛒
<?php if($totalCart > 0): ?>
<span class="cart-badge"><?= $totalCart ?></span>
<?php endif; ?>
</a>
<a href="profil.php" class="cart-icon">👤</a>
</div>
</header>

<div class="container">

<div class="breadcrumb">
&lt; <a href="dashboard.php">Beranda</a> / syarat, ketentuan & kebijakan privasi
</div>

<div class="title">Syarat, ketentuan & kebijakan privasi</div>

<div class="content-text">

<p><b>Syarat, Ketentuan & Kebijakan Privasi</b><br>
Oxsal Store</p>

<p>
Dengan menggunakan layanan Oxsal Store, pengguna dianggap telah membaca, memahami, dan menyetujui syarat, ketentuan, serta kebijakan privasi berikut.
</p>

<p><b>1. Penggunaan Layanan</b><br>
Oxsal Store adalah platform e-commerce yang menyediakan layanan jual beli produk. Pengguna wajib menggunakan layanan ini secara wajar dan tidak melanggar hukum yang berlaku.
</p>

<p><b>2. Akun Pengguna</b><br>
Pengguna bertanggung jawab atas keamanan akun dan data login masing-masing. Segala aktivitas yang terjadi melalui akun pengguna menjadi tanggung jawab pemilik akun.
</p>

<p><b>3. Data yang Dikumpulkan</b><br>
Kami mengumpulkan data pengguna seperti:
</p>

<ul>
<li>Nama</li>
<li>Email</li>
<li>Nomor ponsel</li>
<li>Alamat pengiriman</li>
</ul>

<p>
Data ini digunakan untuk keperluan transaksi, pengiriman, dan peningkatan layanan.
</p>

<p><b>4. Keamanan & Privasi Data</b><br>
Oxsal Store berkomitmen menjaga kerahasiaan data pengguna dan tidak membagikan data pribadi kepada pihak ketiga tanpa izin pengguna, kecuali diwajibkan oleh hukum.
</p>

<p><b>5. Transaksi & Pesanan</b><br>
Semua transaksi yang dilakukan melalui Oxsal Store mengikuti alur pemesanan yang tersedia. Status pesanan akan ditampilkan sesuai proses (diproses, dikirim, atau selesai).
</p>

<p><b>6. Perubahan Layanan</b><br>
Oxsal Store berhak melakukan perubahan pada layanan, fitur, maupun kebijakan untuk meningkatkan kualitas platform tanpa pemberitahuan sebelumnya.
</p>

<p><b>7. Persetujuan</b><br>
Dengan mendaftar dan menggunakan Oxsal Store, pengguna menyetujui seluruh syarat, ketentuan, dan kebijakan privasi yang berlaku.
</p>

</div>

</div>

<footer>
© OxsalStore 2026. All Rights Reserved .
</footer>

</body>
</html>