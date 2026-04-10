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

/* STATUS */
$status = strtolower(trim($transaksi['status']));
$warna = "#9e9e9e";

if($status == "diproses") $warna = "#ffb74d";
elseif($status == "dikirim") $warna = "#64b5f6";
elseif($status == "selesai") $warna = "#81c784";
elseif($status == "dibatalkan") $warna = "#e57373";

/* PROGRESS */
$progress = 0;
$keterangan = "";

switch($status){
    case "pending": $progress=10; $keterangan="Menunggu pembayaran"; break;
    case "diproses": $progress=40; $keterangan="Pesanan sedang diproses"; break;
    case "dikirim": $progress=75; $keterangan="Pesanan dalam perjalanan"; break;
    case "selesai": $progress=100; $keterangan="Pesanan telah diterima"; break;
    case "dibatalkan": $progress=100; $keterangan="Pesanan dibatalkan"; break;
}

/* STEP SHOPEE */
$step = 1;
if($status == 'diproses') $step = 2;
elseif($status == 'dikirim') $step = 3;
elseif($status == 'selesai') $step = 4;
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Pesanan</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f5f1ea;}
header{background:#6f4e37;color:white;padding:15px 60px;display:flex;justify-content:space-between;align-items:center;}
.logo{display:flex;align-items:center;gap:10px;font-weight:600;font-size:20px;}
.logo img{width:35px;}
.container{padding:40px 80px;}
.back-btn{background:#e6d3b3;padding:10px 18px;border-radius:25px;text-decoration:none;color:#333;display:inline-block;margin-bottom:20px;}
.top-grid{display:grid;grid-template-columns:2fr 1fr;gap:30px;}
.card{background:white;border-radius:15px;box-shadow:0 6px 15px rgba(0,0,0,0.08);overflow:hidden;}
.order-head{display:flex;justify-content:space-between;padding:20px;border-bottom:1px solid #eee;}
.status{padding:6px 15px;border-radius:20px;color:white;font-size:13px;}

.progress-box{padding:20px;}
.progress-bar{background:#eee;border-radius:20px;overflow:hidden;}
.progress-fill{
height:18px;
color:white;
font-size:11px;
text-align:center;
line-height:18px;
border-radius:20px;
transition:0.6s ease;
}

/* 🔥 SHOPEE TRACKING */
.tracking-shopee{
display:flex;
justify-content:space-between;
margin-top:25px;
position:relative;
}

.tracking-shopee::before{
content:'';
position:absolute;
top:18px;
left:0;
right:0;
height:4px;
background:#ddd;
z-index:0;
}

.step{
text-align:center;
width:100%;
position:relative;
z-index:1;
}

.circle{
width:35px;
height:35px;
border-radius:50%;
background:#ccc;
color:white;
display:flex;
align-items:center;
justify-content:center;
margin:0 auto 8px;
font-size:14px;
font-weight:bold;
}

.step.active .circle{background:#2196f3;}
.step.done .circle{background:#4caf50;}

.step p{font-size:12px;color:#555;}

.item{display:flex;gap:15px;padding:15px 20px;border-bottom:1px solid #eee;}
.item img{width:70px;border-radius:10px;}
.item-name{flex:1;}
.item-price{font-weight:600;}
.total{text-align:right;padding:20px;font-weight:600;}
.info-box{background:white;padding:20px;border-radius:15px;}
.row{display:flex;justify-content:space-between;margin-bottom:10px;}

.rating-box{margin-top:10px;background:#f3f7ed;padding:10px;border-radius:10px;}
.star{cursor:pointer;color:#ccc;}
.star.active{color:gold;}
.btn-rating{margin-top:6px;padding:6px 12px;border:none;border-radius:8px;background:#4caf50;color:white;}
</style>
</head>

<body>

<header>
<div class="logo">
<img src="../../assets/oxsal.png">
OXSAL STORE
</div>
</header>

<div class="container">

<a href="riwayat_pesanan.php" class="back-btn">⬅ Kembali</a>

<h2>Detail Pesanan</h2>

<div class="top-grid">

<div class="card">

<div class="order-head">
<div>
<b>#<?= $transaksi['id'] ?></b><br>
Tanggal : <?= date('d F Y',strtotime($transaksi['tanggal'])) ?>
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

<p><?= $keterangan ?></p>

<!-- 🔥 TRACKING SHOPEE -->
<div class="tracking-shopee">

<div class="step <?= ($step>=1?'done':'') ?>">
<div class="circle">1</div>
<p>Dibuat</p>
</div>

<div class="step <?= ($step==2?'active':'') ?> <?= ($step>2?'done':'') ?>">
<div class="circle">2</div>
<p>Diproses</p>
</div>

<div class="step <?= ($step==3?'active':'') ?> <?= ($step>3?'done':'') ?>">
<div class="circle">3</div>
<p>Dikirim</p>
</div>

<div class="step <?= ($step==4?'active done':'') ?>">
<div class="circle">4</div>
<p>Selesai</p>
</div>

</div>

</div>

<?php while($item = mysqli_fetch_assoc($qDetail)): 
$total += $item['subtotal'];

$cek = mysqli_query($conn,"
SELECT * FROM ulasan 
WHERE user_id='$user_id'
AND produk_id='{$item['produk_id']}'
AND transaksi_id='$transaksi_id'
");
$sudah = mysqli_num_rows($cek);
?>

<div class="item">
<img src="../../uploads/<?= $item['gambar'] ?>">

<div class="item-name">
<?= $item['nama'] ?> x<?= $item['qty'] ?>

<?php if($status == 'selesai'): ?>
<div class="rating-box">

<?php if($sudah == 0): ?>
<form method="POST">
<div id="stars<?= $item['produk_id'] ?>">
<?php for($i=1;$i<=5;$i++): ?>
<span class="star" onclick="setRating(<?= $i ?>,<?= $item['produk_id'] ?>)">★</span>
<?php endfor; ?>
</div>

<input type="hidden" name="rating" id="rating<?= $item['produk_id'] ?>">
<input type="hidden" name="produk_id" value="<?= $item['produk_id'] ?>">
<input type="hidden" name="transaksi_id" value="<?= $transaksi_id ?>">

<button class="btn-rating" name="kirim_ulasan">Kirim</button>
</form>
<?php else: ?>
<span style="color:green;">✔ Sudah dinilai</span>
<?php endif; ?>

</div>
<?php endif; ?>

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
<h3>Ringkasan</h3>
<div class="row"><span>Total</span><span>Rp <?= number_format($total) ?></span></div>
</div>

</div>

</div>

<script>
function setRating(rating,id){
let stars=document.querySelectorAll("#stars"+id+" .star");
document.getElementById("rating"+id).value=rating;
stars.forEach((s,i)=>{s.classList.toggle("active",i<rating);});
}
</script>

</body>
</html>