<?php
require_once "../../middleware/auth.php";
require_once __DIR__ . "/../../config/database.php";

checkRole('user');

if(!isset($_GET['id'])){
    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET['id']);

/* AMBIL PRODUK */
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if(!$data){
    echo "Produk tidak ditemukan";
    exit;
}

/* AMBIL RATING REALTIME */
$qRating = mysqli_query($conn,"
SELECT 
    AVG(rating) as rata_rating,
    COUNT(*) as total_ulasan
FROM ulasan
WHERE produk_id = $id
");

$ratingData = mysqli_fetch_assoc($qRating);

$rata = round($ratingData['rata_rating'],1);
$total_ulasan = $ratingData['total_ulasan'];
if(!$rata) $rata = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>OXSAL STORE - Detail Produk</title>
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
.container{padding:50px 80px;position:relative;}

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

/* MAIN GRID */
.main-grid{
    display:grid;
    grid-template-columns:1.2fr 1.5fr 1fr;
    gap:50px;
}

/* LEFT IMAGE */
.left-column{
    display:flex;
    flex-direction:column;
    gap:30px;
}
.image-box{
    background:#fff;
    padding:40px;
    border-radius:15px;
    display:flex;
    justify-content:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
.image-box img{
    max-width:100%;
    max-height:300px;
}

/* SHIPPING BOX BELOW IMAGE */
.shipping{
    background:#fff8f0;
    padding:20px 25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}
.shipping p{
    display:flex;
    justify-content:space-between;
    margin:10px 0;
}
.shipping strong{
    font-weight:600;
}

/* CENTER INFO */
.detail-info h1{
    font-size:36px;
    margin-bottom:10px;
}
.rating{
    color:#FFD700;
    font-size:20px;
    margin:10px 0;
}
.detail-info p{
    margin-bottom:20px;
    font-size:14px;
}
.detail-info h3{
    margin-bottom:10px;
}
.detail-info ul{
    padding-left:18px;
    margin-bottom:20px;
}
.detail-info ul li{
    margin-bottom:5px;
    font-size:13px;
}

/* PRICE AND BUTTON */
.price-box{
    display:flex;
    justify-content:flex-start;
    align-items:center;
    gap:15px;
    margin-top:10px;
}
.price-tag{
    background:#e6f2dc;
    padding:12px 20px;
    border-radius:12px;
    font-weight:600;
}
.cart-btn{
    display:inline-block;
    background:#7b5e3c;
    color:white;
    padding:12px 20px;
    border-radius:12px;
    text-decoration:none;
    font-weight:600;
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
    transition:0.2s;
}
.cart-btn:hover{
    background:#5a4031;
}

/* RIGHT WATERMARK */
.bg-logo{
    position:absolute;
    right:50px;
    top:50%;
    transform:translateY(-50%);
    opacity:0.15;
}
.bg-logo img{
    width:250px;
}
</style>
</head>
<body>

<header>
    <div class="logo">
        <img src="../../assets/oxsal.png" class="logo-img">
        <span>OXSAL STORE</span>
    </div>
</header>

<div class="container">

<a href="dashboard.php" class="back-btn">⬅ Kembali</a>

<div class="main-grid">

<!-- LEFT COLUMN -->
<div class="left-column">
    <div class="image-box">
        <img src="../../uploads/<?= $data['gambar']; ?>" alt="<?= htmlspecialchars($data['nama']); ?>">
    </div>
    <div class="shipping">
        <p><span>Subtotal :</span><span>Rp <?= number_format($data['harga']); ?></span></p>
        <p><span>Ongkos Kirim :</span><span>Rp 20.000</span></p>
        <p><strong>Total :</strong><strong>Rp <?= number_format($data['harga']+20000); ?></strong></p>
    </div>
</div>

<!-- CENTER COLUMN -->
<div class="detail-info">
    <h1><?= htmlspecialchars($data['nama']); ?></h1>
    <div class="rating">
        <?php
        $full = floor($rata);
        for($i=1;$i<=5;$i++){
            echo ($i <= $full) ? "★" : "☆";
        }
        ?>
        (<?= $total_ulasan ?> ulasan)
    </div>
    <p><?= htmlspecialchars(substr($data['deskripsi'],0,120)); ?></p>
    <h3>Deskripsi Produk</h3>
    <ul>
        <?php
        $descLines = explode("\n",$data['deskripsi']);
        foreach($descLines as $line){
            echo "<li>".htmlspecialchars($line)."</li>";
        }
        ?>
    </ul>

    <div class="price-box">
        <div class="price-tag">Rp <?= number_format($data['harga']); ?></div>
        <a href="tambah-keranjang.php?id=<?= $data['id']; ?>" class="cart-btn">🛒 Masukkan ke Keranjang</a>
    </div>
</div>

<!-- RIGHT COLUMN -->
<div class="bg-logo">
    <img src="../../assets/Hanz-Store.logo.png">
</div>

</div> <!-- end main-grid -->

</div> <!-- end container -->

</body>
</html>