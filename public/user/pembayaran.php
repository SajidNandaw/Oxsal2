<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('user');

if(!isset($_SESSION['id'])){
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['id'];
$transaksi_id = $_GET['id'] ?? 0;

if($transaksi_id == 0){
    header("Location: dashboard.php");
    exit;
}

/* TRANSAKSI */
$transaksi = mysqli_query($conn,"
SELECT * FROM transaksi
WHERE id='$transaksi_id' AND user_id='$user_id'
");

$data = mysqli_fetch_assoc($transaksi);
if(!$data){
    die("Transaksi tidak ditemukan");
}

/* PRODUK */
$produk = mysqli_query($conn,"
SELECT detail_transaksi.*, produk.nama, produk.gambar
FROM detail_transaksi
JOIN produk ON detail_transaksi.produk_id = produk.id
WHERE detail_transaksi.transaksi_id='$transaksi_id'
");

$dataProduk = [];
$total_produk = 0;

while($row = mysqli_fetch_assoc($produk)){
    $row['subtotal'] = $row['subtotal'] ?? 0;
    $total_produk += $row['subtotal'];
    $dataProduk[] = $row;
}

if(empty($dataProduk)){
    echo "<script>alert('Produk tidak ditemukan');window.location='dashboard.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembayaran - OXSAL STORE</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f6f0e3;padding:40px;color:#3b2e2c;}

.container{
max-width:800px;
margin:auto;
display:flex;
flex-direction:column;
gap:20px;
}

/* Card Ringkasan Pesanan */
.card{
background:#fff;
border-radius:18px;
padding:25px;
box-shadow:0 6px 12px rgba(0,0,0,0.1);
}

.item{
display:flex;
justify-content:space-between;
align-items:center;
padding:12px 0;
border-bottom:1px solid #eee;
}

.item:last-child{border-bottom:none;}

.item-left{
display:flex;
align-items:center;
gap:15px;
}

.item-left img{
width:70px;
height:70px;
object-fit:cover;
border-radius:10px;
background:#fff;
}

.item-name{
font-weight:500;
}

/* Total */
.total-box{
margin-top:15px;
font-size:15px;
display:flex;
flex-direction:column;
gap:5px;
}

.total-box div{
display:flex;
justify-content:space-between;
}

.total-final{
font-size:18px;
font-weight:700;
border-top:1px solid #ccc;
padding-top:8px;
}

/* Referensi Pembayaran */
.ref-card{
background:#bda17c;
border-radius:18px;
padding:20px 25px;
box-shadow:0 6px 12px rgba(0,0,0,0.15);
display:flex;
justify-content:space-between;
align-items:center;
color:white;
}

.ref-text{
font-size:18px;
font-weight:600;
}

.copy-btn{
background:white;
border:none;
padding:10px 12px;
border-radius:50%;
cursor:pointer;
box-shadow:0 2px 6px rgba(0,0,0,0.2);
}

/* Petunjuk pembayaran */
.guide{
background:#ffd1d1;
border-radius:18px;
padding:25px;
line-height:1.7;
box-shadow:0 6px 12px rgba(0,0,0,0.15);
font-size:14px;
}

/* Upload bukti */
.upload input[type=file]{
width:100%;
padding:10px;
border-radius:12px;
border:1px solid #ccc;
}

/* Tombol Kirim */
.submit-btn{
margin-top:15px;
width:100%;
padding:15px;
border:none;
border-radius:12px;
background:#7b5e3c;
color:white;
font-size:16px;
font-weight:600;
cursor:pointer;
transition:0.2s;
}
.submit-btn:hover{
background:#5a4031;
}
</style>
</head>
<body>

<div class="container">

<!-- Ringkasan Pesanan -->
<div class="card">
<?php foreach($dataProduk as $p): ?>
<div class="item">
<div class="item-left">
<img src="../../uploads/<?= $p['gambar'] ?>" alt="<?= htmlspecialchars($p['nama']); ?>">
<div class="item-name"><?= $p['nama'] ?> <?= $p['qty'] ?>x</div>
</div>
<div>Rp <?= number_format($p['subtotal']) ?></div>
</div>
<?php endforeach; ?>

<div class="total-box">
<div><span>Ongkos Kirim :</span><span>Rp 15.000</span></div>
<div><span>Sub total produk :</span><span>Rp <?= number_format($total_produk) ?></span></div>
<div class="total-final"><span>Total :</span><span>Rp <?= number_format($total_produk + 15000) ?></span></div>
</div>
</div>

<!-- Referensi Pembayaran -->
<div class="ref-card">
<div>
<div>Referensi Pembayaran</div>
<div class="ref-text" id="ref">97865434567755753</div>
</div>
<button class="copy-btn" onclick="copyRef()">📋</button>
</div>

<!-- Petunjuk -->
<div class="guide">
<b>Untuk menyelesaikan pembayaran, silakan lakukan transfer melalui aplikasi M-Banking dengan langkah berikut :</b>
<br><br>
1. Buka aplikasi M-Banking di ponsel Anda<br>
2. Login menggunakan PIN / Password / Biometrik<br>
3. Pilih menu “Transfer”<br>
4. Pilih “Ke Rekening Lain / Antar Bank”<br>
5. Pilih bank tujuan sesuai informasi pembayaran<br>
6. Masukkan nomor rekening tujuan<br>
7. Masukkan jumlah transfer Rp <?= number_format($total_produk + 15000) ?><br>
8. Periksa kembali detail pembayaran<br>
9. Masukkan PIN M-Banking / OTP<br>
10. Transaksi berhasil – simpan bukti transfer
</div>

<!-- Form Upload Bukti -->
<form action="upload_bukti.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="transaksi_id" value="<?= $transaksi_id ?>">
<div class="upload">
<input type="file" name="bukti" required>
</div>
<button class="submit-btn">Kirim</button>
</form>

</div>

<script>
function copyRef(){
let text = document.getElementById("ref").innerText;
navigator.clipboard.writeText(text);
alert("Nomor referensi disalin!");
}
</script>

</body>
</html>