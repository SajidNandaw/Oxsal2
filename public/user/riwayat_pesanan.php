<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('user');

$user_id = $_SESSION['id'];

/* ================= FILTER STATUS ================= */
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'semua';

/* mapping UI ke DB */
$status_db = $status_filter;
if($status_filter == 'diproses'){
    $status_db = 'pending';
}

/* ================= QUERY ================= */
if($status_filter == 'semua'){
    $query = mysqli_query($conn,"
    SELECT DISTINCT
        transaksi.id,
        transaksi.tanggal,
        transaksi.total,
        transaksi.status,
        produk.nama AS nama_produk,
        produk.gambar
    FROM transaksi
    JOIN detail_transaksi 
        ON transaksi.id = detail_transaksi.transaksi_id
    JOIN produk 
        ON detail_transaksi.produk_id = produk.id
    WHERE transaksi.user_id='$user_id'
    ORDER BY transaksi.tanggal DESC
    ");
}else{

    // 🔥 FIX DI SINI
    if($status_db == 'pending'){
        $filter_condition = "(transaksi.status = 'pending' OR transaksi.status IS NULL)";
    } else {
        $filter_condition = "transaksi.status = '$status_db'";
    }

    $query = mysqli_query($conn,"
    SELECT DISTINCT
        transaksi.id,
        transaksi.tanggal,
        transaksi.total,
        transaksi.status,
        produk.nama AS nama_produk,
        produk.gambar
    FROM transaksi
    JOIN detail_transaksi 
        ON transaksi.id = detail_transaksi.transaksi_id
    JOIN produk 
        ON detail_transaksi.produk_id = produk.id
    WHERE transaksi.user_id='$user_id'
    AND $filter_condition
    ORDER BY transaksi.tanggal DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Pesanan</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#e9e3da;
}

header{
background:#6f4e37;
color:white;
padding:15px 40px;
display:flex;
justify-content:space-between;
align-items:center;
}

.logo{
font-weight:700;
font-size:22px;
}

.container{
width:900px;
margin:auto;
margin-top:40px;
}

.back-btn{
background:#d9c2a3;
padding:10px 20px;
border-radius:25px;
text-decoration:none;
color:black;
display:inline-block;
margin-bottom:20px;
}

.title{
font-size:28px;
font-weight:700;
margin-bottom:15px;
}

.filter{
display:flex;
gap:10px;
margin-bottom:20px;
}

.filter a{
text-decoration:none;
padding:6px 14px;
border-radius:8px;
background:#eee;
color:black;
font-size:13px;
}

.filter a.active{
background:#1e8e3e;
color:white;
}

.sort{
float:right;
margin-top:-45px;
}

.sort select{
padding:8px 12px;
border-radius:10px;
border:1px solid #ccc;
}

.order-card{
background:white;
border-radius:12px;
padding:15px;
margin-bottom:15px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 3px 8px rgba(0,0,0,0.1);
}

.order-left{
display:flex;
align-items:center;
gap:15px;
}

.product-img{
width:70px;
height:70px;
border-radius:10px;
overflow:hidden;
background:#f2f2f2;
}

.product-img img{
width:100%;
height:100%;
object-fit:cover;
}

.order-info{
font-size:13px;
color:#444;
}

.order-info b{
font-size:15px;
}

.order-total{
margin-left:20px;
font-size:13px;
color:#555;
}

.order-right{
display:flex;
flex-direction:column;
align-items:flex-end;
gap:8px;
}

.detail-btn{
background:#e9d7b7;
padding:6px 14px;
border-radius:20px;
text-decoration:none;
color:black;
font-size:13px;
}

.status{
padding:6px 14px;
border-radius:20px;
font-size:13px;
font-weight:500;
}

.diproses{ background:#fff176; }
.dikirim{ background:#90caf9; }
.selesai{ background:#a5d6a7; }

.empty{
text-align:center;
margin-top:50px;
color:#666;
}
</style>
</head>

<body>

<header>
<div class="logo">OXSAL STORE</div>
<div>👤</div>
</header>

<div class="container">

<a href="profile.php" class="back-btn">⬅ Kembali</a>

<div class="title">Riwayat pesanan</div>

<!-- FILTER -->
<div class="filter">
<a href="?status=semua" class="<?= $status_filter=='semua' ? 'active' : '' ?>">Semua</a>
<a href="?status=dikirim" class="<?= $status_filter=='dikirim' ? 'active' : '' ?>">Dikirim</a>
<a href="?status=selesai" class="<?= $status_filter=='selesai' ? 'active' : '' ?>">Selesai</a>
</div>

<div class="sort">
<select>
<option>Terbaru</option>
<option>Terlama</option>
</select>
</div>

<?php if(mysqli_num_rows($query) > 0): ?>

<?php while($row = mysqli_fetch_assoc($query)): ?>

<?php
$status = $row['status'];

/* mapping DB ke UI */
if(!$status || $status == 'pending'){
    $status = 'diproses';
}
?>

<div class="order-card">

<div class="order-left">

<div class="product-img">
<img src="../../uploads/<?= $row['gambar']; ?>">
</div>

<div class="order-info">
<b>#<?= $row['id']; ?></b><br>
<?= date("d F Y", strtotime($row['tanggal'])); ?><br>
<?= $row['nama_produk']; ?>
</div>

<div class="order-total">
Total Rp <?= number_format($row['total']); ?>
</div>

</div>

<div class="order-right">

<a href="detail_pesanan.php?id=<?= $row['id']; ?>" class="detail-btn">
Detail >
</a>

<span class="status <?= $status; ?>">
<?= ucfirst($status); ?>
</span>

</div>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="empty">
Belum ada riwayat pesanan
</div>

<?php endif; ?>

</div>

</body>
</html>