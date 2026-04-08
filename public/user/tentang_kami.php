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
<title>Tentang Oxsal Store</title>

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
font-weight:600;
font-size:20px;
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
text-align:center;
}

.breadcrumb{
text-align:left;
margin-bottom:20px;
font-size:14px;
}

.title{
font-size:36px;
font-weight:600;
margin-bottom:20px;
}

.desc{
max-width:700px;
margin:0 auto 40px;
font-size:14px;
line-height:1.8;
color:#333;
}

.box{
background:#b89a7c;
padding:30px;
border-radius:12px;
display:flex;
justify-content:space-between;
gap:30px;
color:white;
}

.box div{flex:1;}

.box h3{
margin-bottom:10px;
}

.box p{
font-size:14px;
line-height:1.7;
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
<div class="logo">
<img src="../../assets/oxsal.png">
OXSAL STORE
</div>

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
&lt; <a href="dashboard.php" style="text-decoration:none;color:#6b4b3e;font-weight:500;">Beranda</a> / Tentang Kami
</div>

<div class="title">
Oxsal Store, Platform Fashion<br>
Generasi Muda
</div>

<div class="desc">
Didirikan pada tahun 2025, Oxsal Store hadir sebagai platform belanja online yang berfokus pada fashion dan gaya hidup anak muda. Kami menghadirkan pengalaman belanja yang simpel, aman, dan cepat, dengan koleksi produk pilihan yang mengikuti tren masa kini.
<br><br>
Oxsal Store berkomitmen menjadi ruang bagi generasi muda untuk menemukan dan mengekspresikan gaya mereka melalui fashion yang relevan dan berkualitas.
</div>

<div class="box">

<div>
<h3>Tujuan kami</h3>
<p>
Kami ingin menjadi wadah yang mempertemukan brand lokal dan konsumen dalam satu ekosistem digital. Melalui teknologi dan kurasi produk yang tepat, kami membantu setiap orang mengekspresikan gaya mereka dengan lebih percaya diri.
</p>
</div>

<div>
<h3>Posisi Kami</h3>
<p>
Oxsal Store hadir untuk generasi muda yang mengutamakan gaya, kenyamanan, dan kemudahan. Kami menawarkan pengalaman belanja fashion online yang simpel, relevan, dan dapat diakses kapan saja tanpa batas.
</p>
</div>

</div>

</div>

<footer>
© OxsalStore 2026. All Rights Reserved .
</footer>

</body>
</html>
