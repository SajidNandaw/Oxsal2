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
<title>Pengiriman Pesanan - Oxsal Store</title>

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
position:relative;
}

.content-text p{
margin-bottom:15px;
}

.content-text ul,
.content-text ol{
margin-left:20px;
margin-bottom:15px;
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
&lt; <a href="dashboard.php">Beranda</a> / Pengiriman
</div>

<div class="title">Pengiriman Pesanan</div>

<div class="content-text">

<p>
Pengiriman pesanan di Oxsal Store dilakukan dengan cepat, aman, dan transparan. Berikut informasi lengkap mengenai proses pengiriman barang hingga pesanan diterima.
</p>

<p><b>Alur Pengiriman :</b></p>

<ol>
<li>
Pesanan Diproses <br>
Setelah pembayaran berhasil, pesanan akan diproses oleh penjual dan disiapkan untuk pengiriman.
</li>

<li>
Pesanan Dikemas <br>
Produk dikemas dengan aman untuk menjaga kualitas barang selama proses pengiriman.
</li>

<li>
Pesanan Dikirim <br>
Barang diserahkan ke jasa pengiriman. Nomor resi akan tersedia di halaman detail pesanan.
</li>

<li>
Pesanan Diterima <br>
Pesanan sampai ke alamat tujuan sesuai dengan estimasi waktu pengiriman.
</li>
</ol>

<p><b>Jasa Pengiriman</b></p>

<p>
Oxsal Store bekerja sama dengan berbagai jasa pengiriman terpercaya, antara lain:
</p>

<ul>
<li>JNE</li>
<li>J&T Express</li>
<li>SiCepat</li>
<li>AnterAja</li>
</ul>

<p>
Jasa pengiriman yang tersedia dapat berbeda tergantung lokasi pengiriman.
</p>

<p><b>Estimasi Waktu Pengiriman</b></p>

<ul>
<li>Dalam kota: 1 – 2 hari kerja</li>
<li>Luar kota: 2 – 5 hari kerja</li>
<li>Daerah terpencil: Menyesuaikan kebijakan jasa pengiriman</li>
</ul>

<p>
Estimasi waktu dapat berubah tergantung kondisi cuaca dan operasional ekspedisi.
</p>

<p><b>Biaya Pengiriman</b></p>

<p>
Biaya pengiriman dihitung secara otomatis saat proses checkout berdasarkan:
</p>

<ul>
<li>Berat produk</li>
<li>Alamat tujuan</li>
<li>Jasa pengiriman yang dipilih</li>
</ul>

<p><b>Pertanyaan Umum (FAQ)</b></p>

<p>
Mengapa pesanan saya belum dikirim? <br>
Pesanan akan diproses maksimal 1x24 jam setelah pembayaran berhasil.
</p>

<p>
Di mana saya bisa melihat nomor resi? <br>
Nomor resi dapat dilihat pada halaman Detail Pesanan di akun Anda.
</p>

<p>
Barang belum sampai atau bermasalah? <br>
Silakan hubungi kami melalui halaman Hubungi Kami untuk mendapatkan bantuan lebih lanjut.
</p>

</div>

</div>

<footer>
© OxsalStore 2026. All Rights Reserved .
</footer>

</body>
</html>
