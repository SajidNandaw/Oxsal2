<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('user');

$user_id = $_SESSION['id'];

if(isset($_POST['kirim_ulasan'])){
    $produk_id = intval($_POST['produk_id']);
    $rating = intval($_POST['rating']);
    $transaksi_id_post = intval($_POST['transaksi_id']);

    if($rating >= 1 && $rating <= 5){
        mysqli_query($conn,"
        INSERT INTO ulasan(user_id, produk_id, transaksi_id, rating)
        VALUES('$user_id','$produk_id','$transaksi_id_post','$rating')
        ");
    }
}

if(!isset($_GET['id'])){
header("Location: riwayat_pesanan.php");
exit;
}

$transaksi_id = intval($_GET['id']);

$qTransaksi = mysqli_query($conn,"
SELECT transaksi.*, users.name, users.alamat
FROM transaksi
JOIN users ON transaksi.user_id = users.id
WHERE transaksi.id='$transaksi_id'
AND transaksi.user_id='$user_id'
");

$transaksi = mysqli_fetch_assoc($qTransaksi);

if(!$transaksi){
echo "Pesanan tidak ditemukan";
exit;
}

$qDetail = mysqli_query($conn,"
SELECT detail_transaksi.*, produk.nama, produk.gambar
FROM detail_transaksi
JOIN produk ON detail_transaksi.produk_id = produk.id
WHERE detail_transaksi.transaksi_id='$transaksi_id'
");

$total = 0;

$status = strtolower($transaksi['status']);
$warna = "#9e9e9e";

if($status == "diproses") $warna = "#ffb74d";
elseif($status == "dikirim") $warna = "#64b5f6";
elseif($status == "selesai") $warna = "#81c784";
elseif($status == "dibatalkan") $warna = "#e57373";

$progress = 0;
$keterangan = "";

switch($status){
    case "diproses": 
        $progress=25; 
        $keterangan="Pesanan Anda sedang diproses oleh penjual";
    break;
    case "dikirim": 
        $progress=60; 
        $keterangan="Pesanan sedang dalam perjalanan menuju alamat Anda";
    break;
    case "selesai": 
        $progress=100; 
        $keterangan="Pesanan telah diterima. Terima kasih telah berbelanja!";
    break;
    case "dibatalkan": 
        $progress=100; 
        $keterangan="Pesanan telah dibatalkan";
    break;
    default: 
        $progress=10; 
        $keterangan="Menunggu konfirmasi pembayaran";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Pesanan</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
background:#f5f1ea;
}

/* HEADER */
header{
background:#6f4e37;
color:white;
padding:15px 60px;
display:flex;
justify-content:space-between;
align-items:center;
}

.logo{
display:flex;
align-items:center;
gap:10px;
font-weight:600;
font-size:20px;
}

.logo img{width:35px;}

/* CONTAINER */
.container{
padding:40px 80px;
}

/* BACK */
.back-btn{
background:#e6d3b3;
padding:10px 18px;
border-radius:25px;
text-decoration:none;
color:#333;
display:inline-block;
margin-bottom:20px;
}

/* GRID */
.top-grid{
display:grid;
grid-template-columns:2fr 1fr;
gap:30px;
}

/* CARD */
.card{
background:white;
border-radius:15px;
box-shadow:0 6px 15px rgba(0,0,0,0.08);
overflow:hidden;
}

.order-head{
display:flex;
justify-content:space-between;
padding:20px;
border-bottom:1px solid #eee;
}

.status{
padding:6px 15px;
border-radius:20px;
color:white;
font-size:13px;
}

.progress-box{padding:20px;}

.progress-bar{
background:#eee;
border-radius:20px;
overflow:hidden;
}

.progress-fill{
height:18px;
color:white;
font-size:11px;
text-align:center;
line-height:18px;
border-radius:20px;
}

/* ITEM */
.item{
display:flex;
align-items:flex-start;
gap:15px;
padding:15px 20px;
border-bottom:1px solid #eee;
}

.item img{
width:70px;
height:70px;
object-fit:cover;
border-radius:10px;
background:#f2f2f2;
}

.item-name{flex:1;font-size:14px;}

.item-price{font-weight:600;color:#444;}

.total{
text-align:right;
padding:20px;
font-weight:600;
}

/* INFO */
.info-box{
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 6px 15px rgba(0,0,0,0.08);
}

.row{
display:flex;
justify-content:space-between;
margin-bottom:10px;
font-size:14px;
}

/* BOTTOM */
.bottom-grid{
display:grid;
grid-template-columns:1fr 2fr;
gap:30px;
margin-top:30px;
}

/* FOOTER */
footer{
background:#6f4e37;
color:white;
margin-top:40px;
text-align:center;
padding:15px;
font-size:14px;
}

/* RATING */
.rating-box{
margin-top:10px;
background:#f3f7ed;
padding:10px;
border-radius:10px;
}

.star{cursor:pointer;color:#ccc;}
.star.active{color:gold;}

.btn-rating{
margin-top:6px;
padding:6px 12px;
border:none;
border-radius:8px;
background:#4caf50;
color:white;
font-size:12px;
cursor:pointer;
}
</style>
</head>

<body>

<header>
<div class="logo">
<img src="../../assets/kiostore2.png">
OXSAL STORE
</div>
<div>👤</div>
</header>

<div class="container">

<a href="riwayat_pesanan.php" class="back-btn">⬅ Kembali</a>

<h2>Detail Pesanan</h2>
<p style="color:#666;font-size:14px;margin-bottom:20px;">
Lihat status pesanan dan rincian pembayaran Anda
</p>

<div class="top-grid">

<div class="card">

<div class="order-head">
<div>
<b>#<?= $transaksi['id'] ?></b><br>
<span style="font-size:13px;color:#555;">
Tanggal pesanan : <?= date('d F Y',strtotime($transaksi['tanggal'])) ?><br>
Estimasi tiba : <?= date('d F Y',strtotime($transaksi['tanggal'].' +3 days')) ?>
</span>
</div>

<div class="status" style="background:<?= $warna ?>;">
<?= ucfirst($transaksi['status']) ?>
</div>
</div>

<div class="progress-box">
<div class="progress-bar">
<div class="progress-fill" style="width:<?= $progress ?>%; background:<?= $warna ?>;">
<?= $progress ?>%
</div>
</div>
<p style="margin-top:8px;font-size:14px;"><?= $keterangan ?></p>
</div>

<?php while($item = mysqli_fetch_assoc($qDetail)): 
$total += $item['subtotal'];
?>

<div class="item">
<img src="../../uploads/<?= $item['gambar'] ?>">

<div class="item-name">
<?= $item['nama'] ?> x<?= $item['qty'] ?>
</div>

<div class="item-price">
Rp <?= number_format($item['subtotal']) ?>
</div>
</div>

<?php endwhile; ?>

<div class="total">
Total : Rp <?= number_format($total) ?>
</div>

</div>

<div class="info-box">
<h3>Ringkasan Pembayaran</h3><br>

<div class="row">
<span>Harga produk</span>
<span>Rp <?= number_format($total) ?></span>
</div>

<div class="row">
<span>Ongkir</span>
<span>Rp 15.000</span>
</div>

<hr><br>

<div class="row">
<b>Total</b>
<b>Rp <?= number_format($total+15000) ?></b>
</div>
</div>

</div>

<div class="bottom-grid">

<div class="info-box">
<h3>Status Pengiriman</h3><br>
<p>No resi : 0237396387</p>
<p>Status : <b style="color:<?= $warna ?>"><?= ucfirst($transaksi['status']) ?></b></p>
</div>

<div class="info-box">
<div style="display:flex;justify-content:space-between;gap:20px;">
<div>
<h3>Alamat Pengiriman</h3>
<p style="font-size:14px;color:#555;">
<?= $transaksi['name'] ?><br>
<?= $transaksi['alamat'] ?>
</p>
</div>

<div>
<h3>Metode Pembayaran</h3>
<p>💳 Transfer Bank</p>
</div>
</div>
</div>

</div>

</div>

<footer>
© OXSAL STORE 2026. All Rights Reserved
</footer>

</body>
</html>