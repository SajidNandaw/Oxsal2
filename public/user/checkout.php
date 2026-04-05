<?php
session_start();

require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('user');

/* ================= CEK LOGIN ================= */
if(!isset($_SESSION['id'])){
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['id'];

/* ================= CEK PILIH PRODUK ================= */
if(!isset($_POST['pilih'])){
    echo "<script>alert('Pilih produk dulu!');window.location='keranjang.php';</script>";
    exit;
}

$pilih = $_POST['pilih'];
$id_list = implode(",", array_map('intval', $pilih));

/* ================= DATA USER ================= */
$userQuery = mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($userQuery) ?: [];

/* ================= DATA KERANJANG ================= */
$dataKeranjang = [];
$total = 0;

$query = mysqli_query($conn,"
SELECT keranjang.*, produk.nama, produk.harga, produk.gambar
FROM keranjang
JOIN produk ON keranjang.produk_id = produk.id
WHERE keranjang.id IN ($id_list)
AND keranjang.user_id='$user_id'
");

if(!$query){
    die("Query Error: " . mysqli_error($conn));
}

while($row = mysqli_fetch_assoc($query)){
    $subtotal = $row['harga'] * $row['jumlah'];
    $total += $subtotal;

    $row['subtotal'] = $subtotal;
    $dataKeranjang[] = $row;
}

if(empty($dataKeranjang)){
    echo "<script>alert('Produk tidak ditemukan');window.location='keranjang.php';</script>";
    exit;
}

/* ================= PROSES CHECKOUT ================= */
if(isset($_POST['checkout'])){

    $metode = $_POST['metode'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $alamat = $_POST['alamat'] ?? '';

    mysqli_query($conn,"
    INSERT INTO transaksi (user_id,total,status,tanggal)
    VALUES ('$user_id','$total','pending',NOW())
    ");

    $transaksi_id = mysqli_insert_id($conn);

    foreach($dataKeranjang as $item){
        $produk_id = $item['produk_id'];
        $qty = $item['jumlah'];
        $subtotal = $item['subtotal'];

        mysqli_query($conn,"
        INSERT INTO detail_transaksi
        (transaksi_id,produk_id,qty,subtotal)
        VALUES
        ('$transaksi_id','$produk_id','$qty','$subtotal')
        ");

        mysqli_query($conn,"
        UPDATE produk
        SET stok = stok - $qty
        WHERE id='$produk_id'
        ");
    }

    mysqli_query($conn,"
    DELETE FROM keranjang
    WHERE id IN ($id_list)
    AND user_id='$user_id'
    ");

    if($metode == "transfer"){
        header("Location: pembayaran.php?id=$transaksi_id");
        exit;
    } else {
        echo "<script>
        alert('Pesanan berhasil (COD)');
        window.location='dashboard.php';
        </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Checkout - OXSAL STORE</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f6f0e3;color:#3b2e2c;}

/* HEADER */
header{
    background:#7b5e3c;
    padding:15px 60px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.logo{display:flex;align-items:center;gap:12px;color:white;font-weight:700;}
.logo img{width:50px;}
.logo span{font-size:26px;font-weight:700;}

/* CONTAINER */
.container{padding:50px 80px;}

/* BACK BUTTON */
.back-btn{
    display:inline-block;
    padding:12px 28px;
    background:#d2b48c;
    border-radius:12px;
    text-decoration:none;
    color:black;
    margin-bottom:40px;
}

/* GRID CHECKOUT */
.checkout-grid{
    display:grid;
    grid-template-columns:1fr 1.2fr;
    gap:40px;
}

/* CARD */
.card{
    background:#fff8f0;
    border-radius:18px;
    padding:25px;
    box-shadow:0 6px 15px rgba(0,0,0,0.1);
}

/* PESANAN ITEM */
.item{
    display:flex;
    align-items:center;
    gap:15px;
    padding:15px 0;
    border-bottom:1px solid #ddd;
}
.item img{
    width:60px;
    height:60px;
    object-fit:cover;
    border-radius:10px;
    background:#fff;
}
.item-name{flex:1;font-size:14px;}

/* TOTAL */
.total-box{
    margin-top:20px;
    font-weight:600;
    display:flex;
    justify-content:space-between;
    font-size:16px;
}

/* INPUT FORM */
.input{
    width:100%;
    padding:12px;
    border-radius:12px;
    border:1px solid #ccc;
    margin-bottom:15px;
    font-size:14px;
}

/* PAYMENT OPTIONS */
.payment-options{
    display:flex;
    gap:20px;
    margin-bottom:15px;
}
.payment{
    flex:1;
    border:1px solid #ccc;
    padding:12px;
    border-radius:12px;
    cursor:pointer;
    text-align:center;
    font-size:14px;
}
.payment input{margin-right:8px;}

/* CHECKOUT BUTTON */
.checkout-btn{
    margin-top:20px;
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:#7b5e3c;
    color:white;
    font-weight:600;
    font-size:16px;
    cursor:pointer;
    transition:0.2s;
}
.checkout-btn:hover{
    background:#5a4031;
}
</style>
</head>
<body>

<header>
<div class="logo">
<img src="../../assets/oxsal.png" class="logo-img">
<span>OXSAL STORE</span>
</div>
<div>👤</div>
</header>

<div class="container">

<a href="keranjang.php" class="back-btn">⬅ Ubah Keranjang</a>

<form method="POST">

<!-- KIRIM ULANG DATA PILIH -->
<?php foreach($pilih as $id): ?>
<input type="hidden" name="pilih[]" value="<?= $id ?>">
<?php endforeach; ?>

<div class="checkout-grid">

<!-- LEFT: RINGKASAN PESANAN -->
<div class="card">
<h2>Ringkasan Pesanan</h2>
<?php foreach($dataKeranjang as $item): ?>
<div class="item">
<img src="../../uploads/<?= $item['gambar'] ?>" alt="<?= htmlspecialchars($item['nama']); ?>">
<div class="item-name">
<b><?= $item['nama'] ?></b><br>
Rp <?= number_format($item['harga']) ?>
</div>
<div>
Rp <?= number_format($item['subtotal']) ?>
</div>
</div>
<?php endforeach; ?>
<div class="total-box">
<span>Total</span>
<span>Rp <?= number_format($total) ?></span>
</div>
</div>

<!-- RIGHT: FORM CHECKOUT -->
<div class="card">
<h2>CheckOut</h2>

<input type="text" name="nama" class="input" required value="<?= $user['name'] ?? '' ?>" placeholder="Nama Penerima">
<input type="text" name="alamat" class="input" required value="<?= $user['alamat'] ?? '' ?>" placeholder="Alamat Lengkap">

<div class="payment-options">
<label class="payment">
<input type="radio" name="metode" value="transfer" required> Transfer Bank
</label>
<label class="payment">
<input type="radio" name="metode" value="cod"> COD
</label>
</div>

<button name="checkout" class="checkout-btn">🛒 Buat Pesanan</button>
</div>

</div>

</form>
</div>

</body>
</html>