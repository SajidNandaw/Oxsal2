<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('petugas');

/* ================= UPDATE STATUS ================= */
if(isset($_POST['update_status'])){
    $id = intval($_POST['transaksi_id']);
    $status = $_POST['status'];

    mysqli_query($conn,"
    UPDATE transaksi SET status='$status'
    WHERE id='$id'
    ");

    header("Location: detail_pesanan.php?id=".$id);
    exit;
}

/* ================= GET DATA ================= */
if(!isset($_GET['id'])){
header("Location: dashboard.php");
exit;
}

$transaksi_id = intval($_GET['id']);

$qTransaksi = mysqli_query($conn,"
SELECT transaksi.*, users.name, users.alamat
FROM transaksi
JOIN users ON transaksi.user_id = users.id
WHERE transaksi.id='$transaksi_id'
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

/* ================= STATUS ================= */
$status = strtolower($transaksi['status']);
$warna = "#8d6e63";

if($status == "pending") $warna = "#bcaaa4";
elseif($status == "dikirim") $warna = "#6d4c41";
elseif($status == "selesai") $warna = "#4e342e";
elseif($status == "dibatalkan") $warna = "#a1887f";

$progress = 0;
$keterangan = "";

switch($status){
    case "pending": 
        $progress=20; 
        $keterangan="Menunggu pembayaran";
    break;
    case "dikirim": 
        $progress=70; 
        $keterangan="Pesanan sedang dikirim";
    break;
    case "selesai": 
        $progress=100; 
        $keterangan="Pesanan selesai";
    break;
    case "dibatalkan": 
        $progress=100; 
        $keterangan="Pesanan dibatalkan";
    break;
    default: 
        $progress=10; 
        $keterangan="Status tidak diketahui";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Pesanan</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{background:#f5f1ea;}

header{
background:#6f4e37;
color:white;
padding:15px 60px;
display:flex;
justify-content:space-between;
align-items:center;
}

.container{padding:40px 80px;}

.back-btn{
background:#e6d3b3;
padding:10px 18px;
border-radius:25px;
text-decoration:none;
color:#333;
display:inline-block;
margin-bottom:20px;
}

.top-grid{
display:grid;
grid-template-columns:2fr 1fr;
gap:30px;
}

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
}

.total{
text-align:right;
padding:20px;
font-weight:600;
}

.info-box{
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 6px 15px rgba(0,0,0,0.08);
}

.bottom-grid{
display:grid;
grid-template-columns:1fr 2fr;
gap:30px;
margin-top:30px;
}

/* 🔥 BUTTON PETUGAS */
.action-box{
margin-top:20px;
}

.btn{
padding:8px 14px;
border:none;
border-radius:8px;
cursor:pointer;
color:white;
margin-right:10px;
}

.btn-kirim{background:#6d4c41;}
.btn-selesai{background:#4e342e;}
.btn-batal{background:#a1887f;}
</style>

</head>

<body>

<header>
<div>OXSAL STORE</div>
<div>👨‍💼</div>
</header>

<div class="container">

<a href="dashboard.php" class="back-btn">⬅ Kembali</a>

<h2>Detail Pesanan</h2>

<div class="top-grid">

<div class="card">

<div class="order-head">
<div>
<b>#<?= $transaksi['id'] ?></b><br>
<span style="font-size:13px;color:#555;">
Tanggal : <?= date('d F Y',strtotime($transaksi['tanggal'])) ?>
</span>
</div>

<div class="status" style="background:<?= $warna ?>;">
<?= ucfirst($status) ?>
</div>
</div>

<div class="progress-box">
<div class="progress-bar">
<div class="progress-fill" style="width:<?= $progress ?>%; background:<?= $warna ?>;">
<?= $progress ?>%
</div>
</div>
<p style="margin-top:8px;"><?= $keterangan ?></p>
</div>

<?php while($item = mysqli_fetch_assoc($qDetail)): 
$total += $item['subtotal'];
?>

<div class="item">
<img src="../../uploads/<?= $item['gambar'] ?>">
<div>
<?= $item['nama'] ?> x<?= $item['qty'] ?><br>
Rp <?= number_format($item['subtotal']) ?>
</div>
</div>

<?php endwhile; ?>

<div class="total">
Total : Rp <?= number_format($total) ?>
</div>

</div>

<div class="info-box">
<h3>Update Status</h3>

<form method="POST" class="action-box">
<input type="hidden" name="transaksi_id" value="<?= $transaksi['id'] ?>">

<button name="status" value="dikirim" class="btn btn-kirim">Kirim</button>
<button name="status" value="selesai" class="btn btn-selesai">Selesai</button>
<button name="status" value="dibatalkan" class="btn btn-batal">Batalkan</button>

<input type="hidden" name="update_status" value="1">
</form>
</div>

</div>

</div>

</body>
</html>