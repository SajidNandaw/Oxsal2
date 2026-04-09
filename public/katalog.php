<?php
session_start();
require_once __DIR__ . "/../config/database.php";

// Ambil keyword pencarian
$search = $_GET['search'] ?? '';

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $query = mysqli_query($conn, "SELECT * FROM produk 
        WHERE nama LIKE '%$search%' 
        ORDER BY id DESC");
} else {
    $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Oxsal Store</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f5f1ec;}
header{background:#6f4e37;padding:15px 60px;display:flex;align-items:center;justify-content:space-between;color:white;}
.logo{display:flex;align-items:center;gap:10px;font-weight:700;font-size:20px;}
.logo img{height:35px;}
.search{flex:1;margin:0 40px;}
.search input{width:100%;padding:12px 20px;border-radius:30px;border:none;outline:none;background:#e7e3de;}
.icons{display:flex;gap:20px;}
.icons a{color:white;text-decoration:none;font-weight:500;}
.hero{display:flex;justify-content:space-between;align-items:center;padding:50px 60px;background:#ede6de;}
.hero-text{width:50%;}
.hero-text h1{font-size:40px;margin-bottom:15px;}
.hero-text p{font-size:16px;color:#555;line-height:1.6;}
.hero button{margin-top:20px;background:#6f4e37;color:white;padding:12px 25px;border:none;border-radius:25px;cursor:pointer;}
.hero img{width:350px;}
.products{padding:40px 60px;display:grid;grid-template-columns:repeat(5,1fr);gap:25px;}
.card{background:#d2b48c;padding:15px;border-radius:10px;transition:0.2s;}
.card:hover{transform:translateY(-5px);}
.card img{width:100%;height:130px;object-fit:contain;margin-bottom:10px;}
.card h3{font-size:14px;margin-bottom:5px;}
.price{font-size:14px;font-weight:600;margin-bottom:5px;}
.sold{font-size:12px;}
.card-link{
    text-decoration:none;
    color:black;
    display:block;
}
footer{background:#c19a6b;margin-top:40px;}
.footer-top{display:grid;grid-template-columns:repeat(4,1fr);padding:40px 60px;gap:30px;}
.footer-bottom{background:#6f4e37;color:white;text-align:center;padding:12px;font-size:13px;}
</style>
</head>

<body>

<header>

<div class="logo">
<img src="../assets/oxsal.png">
OXSAL STORE
</div>

<div class="search">
<form method="GET">
<input type="text" name="search" placeholder="Cari di Oxsal Store" value="<?= htmlspecialchars($search) ?>">
</form>
</div>

<div class="icons">
<a href="login.php">Masuk</a>
<a href="register.php">Daftar</a>
</div>

</header>

<!-- HERO -->
<div class="hero">
<div class="hero-text">
<h1>Gaya Kamu,<br>Pilihan Kamu</h1>
<p>Oxsal Store menghadirkan fashion lokal dan streetwear berkualitas</p>
<button onclick="window.location.href='#produk'">Belanja Sekarang</button>
</div>

<img src="../assets/Group 3.png">
</div>

<!-- PRODUK -->
<div class="products" id="produk">

<?php if(mysqli_num_rows($query) > 0): ?>
<?php while($row = mysqli_fetch_assoc($query)): ?>

<a href="login.php" class="card-link">

<div class="card">

<?php if(!empty($row['gambar'])): ?>
<img src="../uploads/<?= $row['gambar']; ?>">
<?php else: ?>
<img src="../assets/no-image.png">
<?php endif; ?>

<h3><?= htmlspecialchars($row['nama']); ?></h3>

<div class="price">
Rp <?= number_format($row['harga']); ?>
</div>

<div class="sold">
<?php 
$jual = $row['terjual'] ?? 0;
echo ($jual >= 1000) ? floor($jual/1000) . " Rb+" : $jual;
?> Terjual
</div>

</div>
</a>

<?php endwhile; ?>
<?php else: ?>
<p>Produk tidak ditemukan.</p>
<?php endif; ?>

</div>

<!-- FOOTER -->
<footer>

<div class="footer-top">
<div>
<h3>Layanan</h3>
<p><a href="login.php">Tentang Kami</a></p>
<p><a href="login.php">Keranjang</a></p>
</div>

<div>
<h3>Kontak</h3>
<p>oxsalstorekc@gmail.com</p>
<p>08123456789</p>
</div>

<div>
<h3>Bantuan</h3>
<p><a href="login.php">Cara Belanja</a></p>
<p><a href="login.php">Pembayaran</a></p>
</div>

<div>
<h3>OXSAL STORE</h3>
<p>Fashion modern & terpercaya</p>
</div>
</div>

<div class="footer-bottom">
© 2026 Oxsal Store
</div>

</footer>

</body>
</html>