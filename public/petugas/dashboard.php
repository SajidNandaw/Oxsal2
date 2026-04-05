<?php
require_once "../../middleware/auth.php";
require_once __DIR__ . "/../../config/database.php";

checkRole('petugas');

/* ================= TOTAL DATA ================= */

$qProduk = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
$totalProduk = mysqli_fetch_assoc($qProduk)['total'] ?? 0;

$qUser = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$totalUser = mysqli_fetch_assoc($qUser)['total'] ?? 0;

$qTransaksi = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi");
$totalTransaksi = mysqli_fetch_assoc($qTransaksi)['total'] ?? 0;

$qProfit = mysqli_query($conn, "SELECT SUM(total) as profit FROM transaksi");
$totalProfit = mysqli_fetch_assoc($qProfit)['profit'] ?? 0;

/* ================= RIWAYAT ================= */

$qRiwayat = mysqli_query($conn, "
    SELECT transaksi.id, transaksi.tanggal, users.name, transaksi.total, transaksi.status
    FROM transaksi
    JOIN users ON transaksi.user_id = users.id
    ORDER BY transaksi.id DESC
    LIMIT 5
");

/* ================= STATISTIK ================= */

$qStatistik = mysqli_query($conn, "
    SELECT MONTH(tanggal) as bulan, SUM(total) as total
    FROM transaksi
    GROUP BY MONTH(tanggal)
");

$dataBulanan = [];
while ($row = mysqli_fetch_assoc($qStatistik)) {
    $dataBulanan[$row['bulan']] = $row['total'];
}

$qTerlaris = mysqli_query($conn, "
    SELECT produk.nama, SUM(detail_transaksi.qty) as total_terjual
    FROM detail_transaksi
    JOIN produk ON detail_transaksi.produk_id = produk.id
    GROUP BY detail_transaksi.produk_id
    ORDER BY total_terjual DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard - Oxsal Store</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif}
body{display:flex;background:#d9d4cc}

/* SIDEBAR */
.sidebar{
    width:250px;
    background:#f3f0ea;
    height:100vh;
    padding:25px;
    position:fixed;
    border-right:2px solid #e5ded5;
}

.logo{display:flex;align-items:center;gap:10px;margin-bottom:30px}
.logo img{width:40px}
.logo-text h2{color:#a67c00}
.logo-text span{font-size:13px;color:#6b5e55}

.menu ul{list-style:none}
.menu ul li{margin-bottom:15px}
.menu ul li a{
    text-decoration:none;
    color:#5c4b3f;
    padding:12px 15px;
    display:block;
    border-radius:12px;
    transition:0.3s;
}
.menu ul li a:hover,
.menu ul li a.active{
    background:#c5ab8f;
}

/* MAIN */
.main{margin-left:250px;padding:40px;width:100%}

/* TOPBAR */
.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:35px;
}

.profile{
    display:flex;
    align-items:center;
    gap:15px;
}

/* Logout */
.logout-btn{
    background:#e6b8b8;
    color:#7a0000;
    padding:8px 15px;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
}

.profile-circle{
    width:45px;
    height:45px;
    background:#7b5a45;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-weight:bold;
}

/* CARDS */
.cards{display:flex;gap:20px;margin-bottom:35px}
.card{
    flex:1;
    background:#f3f0ea;
    padding:25px;
    border-radius:15px;
    box-shadow:0 6px 15px rgba(0,0,0,0.08);
}
.card h3{font-size:26px;color:#5c4b3f}

/* TABLE */
.table-box{
    background:#f3f0ea;
    padding:25px;
    border-radius:15px;
    box-shadow:0 6px 15px rgba(0,0,0,0.08);
    margin-bottom:30px;
}
table{width:100%;border-collapse:collapse}
th,td{padding:14px;border-bottom:1px solid #ddd}
th{background:#e5ded5}

/* BOTTOM */
.bottom-section{display:flex;gap:20px}

/* STATISTIK STYLE BARU */
.statistik{
    flex:2;
    background:#b89572;
    padding:25px;
    border-radius:18px;
    color:white;
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

.chart-container{
    display:flex;
    align-items:flex-end;
    gap:18px;
    height:220px;
    margin-top:30px;
}

.bar-item{display:flex;flex-direction:column;align-items:center}
.bar-vertical{
    width:30px;
    background:#f3e2c7;
    border-radius:10px;
    transition:0.3s;
}
.bar-vertical:hover{
    background:#fff;
}
.bar-item small{margin-top:8px;color:#fff}

/* PRODUK */
.produk{
    flex:1;
    background:#f3f0ea;
    padding:25px;
    border-radius:15px;
    box-shadow:0 6px 15px rgba(0,0,0,0.08);
}
.produk-item{
    display:flex;
    justify-content:space-between;
    margin-bottom:12px;
    padding:10px;
    background:#e5ded5;
    border-radius:10px;
}
</style>
</head>

<body>

<div class="sidebar">
    <div class="logo">
        <img src="../../assets/oxsal.png">
        <div class="logo-text">
            <h2>OXSAL</h2>
            <span>Dashboard</span>
        </div>
    </div>

    <div class="menu">
        <ul>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="produk.php">Produk</a></li>
            <li><a href="user.php">User</a></li>
            <li><a href="laporan.php">Laporan</a></li>
            <li><a href="pesanan.php">Pesanan</a></li>
        </ul>
    </div>
</div>

<div class="main">

<div class="topbar">
    <h2>Dashboard</h2>
    <div class="profile">
        <a href="../logout.php" class="logout-btn">Logout</a>
        <span>Halo, Petugas 👋</span>
        <div class="profile-circle">PS</div>
    </div>
</div>

<div class="cards">
    <div class="card">
        <h3><?= $totalUser ?></h3>
        <p>Total User</p>
    </div>
    <div class="card">
        <h3>Rp <?= number_format($totalProfit) ?></h3>
        <p>Total Profit</p>
    </div>
    <div class="card">
        <h3><?= $totalTransaksi ?></h3>
        <p>Total Transaksi</p>
    </div>
</div>

<div class="table-box">
<h3>Riwayat Transaksi</h3>
<table>
<tr>
<th>ID</th>
<th>Tanggal</th>
<th>Pembeli</th>
<th>Total</th>
<th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($qRiwayat)) : ?>
<tr>
<td>#<?= $row['id']; ?></td>
<td><?= $row['tanggal']; ?></td>
<td><?= $row['name']; ?></td>
<td>Rp <?= number_format($row['total']); ?></td>
<td><?= $row['status']; ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>

<div class="bottom-section">

<div class="statistik">
<h3>Statistik Penjualan</h3>

<div class="chart-container">
<?php
$bulanNama=[1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"Mei",6=>"Jun",7=>"Jul",8=>"Agu",9=>"Sep",10=>"Okt",11=>"Nov",12=>"Des"];
$maxValue = !empty($dataBulanan) ? max($dataBulanan) : 0;

for($i=1;$i<=12;$i++):
$value=$dataBulanan[$i]??0;
$height=($maxValue>0)?($value/$maxValue)*200:0;
?>
<div class="bar-item">
<div class="bar-vertical" style="height:<?= $height ?>px;"></div>
<small><?= $bulanNama[$i]; ?></small>
</div>
<?php endfor; ?>
</div>
</div>

<div class="produk">
<h3>Produk Terlaris</h3>
<?php $rank=1; while($row=mysqli_fetch_assoc($qTerlaris)): ?>
<div class="produk-item">
<span><?= $row['nama']; ?> (<?= $row['total_terjual']; ?>)</span>
<strong>#<?= $rank++; ?></strong>
</div>
<?php endwhile; ?>
</div>

</div>

</div>
</body>
</html>