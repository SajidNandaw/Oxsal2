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
<title>Cara Belanja - Oxsal Store</title>

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
margin-bottom:10px;
}

.subtitle{
margin-bottom:30px;
color:#333;
}

.steps{
background:#b89a7c;
border-radius:10px;
overflow:hidden;
}

.step{
display:flex;
align-items:flex-start;
gap:20px;
padding:25px;
border-bottom:1px solid rgba(0,0,0,0.1);
color:white;
}

.step:last-child{border-bottom:none;}

.number{
background:#f1c27d;
color:#6b4b3e;
width:35px;
height:35px;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
font-weight:600;
}

.step-content h3{
margin-bottom:5px;
}

.step-content p{
font-size:14px;
line-height:1.6;
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
<img src="../../assets/oxsal.png">
OXSAL STORE
</a>

<div class="search-box">
<input type="text" placeholder="Cari di Oxsal Store">
</div>

<div class="header-icons">
<a href="keranjang.php" class="cart-icon">🛒
<?php if($totalCart > 0): ?>
<span class="cart-badge"><?= $totalCart ?></span>
<?php endif; ?>
</a>
<a href="profile.php" class="cart-icon">👤</a>
</div>
</header>

<div class="container">

<div class="breadcrumb">
&lt; <a href="dashboard.php">Beranda</a> / Metode Pembayaran
</div>

<div class="title">Cara Belanja</div>

<div class="subtitle">
Ikuti cara cara berikut untuk berbelanja di Oxsal Store dengan mudah
</div>

<div class="steps">

<div class="step">
<div class="number">1</div>
<div class="step-content">
<h3>Cari Produk</h3>
<p>Gunakan kolom pencarian di atas untuk menemukan barang yang ingin Anda beli.</p>
</div>
</div>

<div class="step">
<div class="number">2</div>
<div class="step-content">
<h3>Tambahkan Ke Keranjang</h3>
<p>Klik tombol "Masukkan ke Keranjang" pada halaman produk untuk menambahkan barang ke keranjang Anda.</p>
</div>
</div>

<div class="step">
<div class="number">3</div>
<div class="step-content">
<h3>Checkout Pesanan</h3>
<p>Masuk ke halaman keranjang, tinjau pesanan Anda, lalu klik tombol "Checkout" untuk melanjutkan ke pembayaran.</p>
</div>
</div>

<div class="step">
<div class="number">4</div>
<div class="step-content">
<h3>Lakukan Pembayaran</h3>
<p>Pilih metode pembayaran yang diinginkan dan selesaikan pembayaran. Anda akan menerima konfirmasi pesanan.</p>
</div>
</div>

</div>

</div>

<footer>
© OxsalStore 2026. All Rights Reserved .
</footer>

</body>
</html>
