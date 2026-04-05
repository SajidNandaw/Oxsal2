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
<title>Metode Pembayaran - Oxsal Store</title>

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
max-width:800px;
line-height:1.8;
color:#333;
position:relative;
}

.content-text p{
margin-bottom:20px;
}

.content-text ol{
margin-left:20px;
}

/* FOOTER */
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
<span class="cart-badge\">\<?= $totalCart ?></span>
<?php endif; ?>
</a>
<a href="profil.php" class="cart-icon">👤</a>
</div>
</header>

<div class="container">

<div class="breadcrumb">
&lt; <a href="dashboard.php">Beranda</a> / Metode Pembayaran
</div>

<div class="title">Metode Pembayaran</div>

<div class="content-text">

<p>
Oxsalters, demi kemudahan kamu bertransaksi di Oxsal Store, kami telah menyediakan berbagai pilihan metode pembayaran transaksi digital.
</p>

<p>
Jika kamu sudah melakukan pembayaran, mohon kesediaannya untuk menunggu proses verifikasi maksimal 1x24 jam. Catatan: apabila kamu ingin mengubah metode pembayaran, silakan checkout ulang dan pilih metode pembayaran yang diinginkan.
</p>

<p>
Oxsalters, Oxsal Store menyediakan dua pilihan metode pembayaran yang dapat kamu gunakan, di antaranya:
</p>

<ol>
<li>Bank</li>
<li>COD (Cash On Delivery)</li>
</ol>

</div>

</div>

<footer>
© OxsalStore 2026. All Rights Reserved .
</footer>

</body>
</html>
