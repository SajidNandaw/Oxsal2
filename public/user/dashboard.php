<?php
require_once "../../middleware/auth.php";
require_once __DIR__ . "/../../config/database.php";

checkRole('user');

$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Oxsal Store</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#f5f1ec;
}

/* HEADER */
header{
background:#6f4e37;
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
font-weight:700;
font-size:20px;
}

.logo img{
height:35px;
width:auto; /* ✅ biar gak gepeng & gak bulat */
}

/* SEARCH */
.search{
flex:1;
margin:0 40px;
}

.search input{
width:100%;
padding:12px 20px;
border-radius:30px;
border:none;
outline:none;
background:#e7e3de;
}

/* ICON */
.icons{
display:flex;
gap:20px;
font-size:20px;
}

.icons a{
color:white;
text-decoration:none;
}

/* HERO */
.hero{
display:flex;
justify-content:space-between;
align-items:center;
padding:50px 60px;
background:#ede6de;
}

.hero-text{
width:50%;
}

.hero-text h1{
font-size:40px;
margin-bottom:15px;
}

.hero-text p{
font-size:16px;
color:#555;
line-height:1.6;
}

.hero button{
margin-top:20px;
background:#6f4e37;
color:white;
padding:12px 25px;
border:none;
border-radius:25px;
cursor:pointer;
font-weight:500;
}

.hero img{
width:350px;
}

/* PRODUK */
.products{
padding:40px 60px;
display:grid;
grid-template-columns:repeat(5,1fr);
gap:25px;
}

.card{
background:#d2b48c;
padding:15px;
border-radius:10px;
transition:0.2s;
}

.card:hover{
transform:translateY(-5px);
}

.card img{
width:100%;
height:130px;
object-fit:contain;
margin-bottom:10px;
}

.card h3{
font-size:14px;
margin-bottom:5px;
}

.price{
font-size:14px;
font-weight:600;
margin-bottom:5px;
}

.sold{
font-size:12px;
color:#333;
}

.card-link{
text-decoration:none;
color:black;
}

/* FOOTER */
footer{
background:#c19a6b;
margin-top:40px;
}

.footer-top{
display:grid;
grid-template-columns:repeat(4,1fr);
padding:40px 60px;
gap:30px;
}

.footer-top h3{
margin-bottom:10px;
}

.footer-top p{
font-size:13px;
margin-bottom:6px;
}

/* LOGO FOOTER */
.footer-logo{
display:flex;
align-items:center;
gap:10px;
margin-bottom:10px;
font-weight:700;
}

.footer-logo img{
height:40px;
width:auto; /* ✅ tidak bulat */
}

.footer-bottom{
background:#6f4e37;
color:white;
text-align:center;
padding:12px;
font-size:13px;
}

.footer-top a{
text-decoration:none;
color:black;
}

.footer-top a:hover{
text-decoration:underline;
}
</style>
</head>

<body>

<header>

<div class="logo">
<img src="../../assets/oxsal.png">
OXSAL STORE
</div>

<div class="search">
<input type="text" placeholder="Cari di Oxsal Store">
</div>

<div class="icons">
<a href="keranjang.php">🛒</a>
<a href="profile.php">👤</a>
</div>

</header>

<!-- HERO -->
<div class="hero">
<div class="hero-text">
<h1>Gaya Kamu,<br>Pilihan Kamu</h1>
<p>Oxsal Store menghadirkan fashion lokal dan streetwear berkualitas untuk gaya harianmu</p>
<button>Belanja Sekarang</button>
</div>

<img src="../../assets/Group 3.png">
</div>

<!-- PRODUK -->
<div class="products">

<?php if(mysqli_num_rows($query) > 0): ?>
<?php while($row = mysqli_fetch_assoc($query)): ?>

<a href="detail_produk.php?id=<?= $row['id']; ?>" class="card-link">
<div class="card">

<?php if(!empty($row['gambar'])): ?>
<img src="../../uploads/<?= $row['gambar']; ?>">
<?php else: ?>
<img src="../../assets/no-image.png">
<?php endif; ?>

<h3><?= $row['nama']; ?></h3>

<div class="price">
Rp <?= number_format($row['harga']); ?>
</div>

<div class="sold">
<?php 
$jual = $row['terjual'] ?? 0;

if($jual >= 1000){
    echo floor($jual/1000) . " Rb+ Terjual";
}else{
    echo $jual . " Terjual";
}
?>
</div>

</div>
</a>

<?php endwhile; ?>
<?php else: ?>
<p style="padding:40px;">Produk belum tersedia.</p>
<?php endif; ?>

</div>

<!-- FOOTER -->
<footer>

<div class="footer-top">

<div>
<h3>Layanan</h3>
<p><a href="tentang_kami.php">Tentang Kami</a></p>
<p><a href="dashboard.php">Produk</a></p>
<p><a href="keranjang.php">Keranjang</a></p>
</div>

<div>
<h3>Hubungi Kami</h3>
<p>oxsalstorekc@gmail.com</p>
<p>0815-3465-8489</p>
<p>Indonesia</p>
</div>

<div>
<h3>Bantuan</h3>
<p><a href="cara_belanja.php">Cara Belanja</a></p>
<p><a href="metode_pembayaran.php">Metode Pembayaran</a></p>
<p><a href="pengiriman.php">Pengiriman</a></p>
<p><a href="syarat_ketentuan.php">Syarat & Ketentuan</a></p>
</div>

<div>
<div class="footer-logo">
<img src="../../assets/oxsal.png">
OXSAL STORE
</div>
<p>Oxsal Store hadir sebagai ruang belanja fashion online yang mendukung gaya modern dengan proses yang mudah dan terpercaya.</p>
</div>

</div>

<div class="footer-bottom">
© OxsalStore 2026. All Rights Reserved.
</div>

</footer>

</body>
</html>