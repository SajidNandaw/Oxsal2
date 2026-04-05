<?php
require_once "../../middleware/auth.php";
require_once __DIR__ . "/../../config/database.php";

checkRole('petugas');

/* ================= DATA ================= */

$penjualan = mysqli_query($conn,"
    SELECT transaksi.id,
           transaksi.tanggal,
           transaksi.total,
           users.name AS pembeli
    FROM transaksi
    JOIN users ON transaksi.user_id = users.id
    ORDER BY transaksi.id DESC
    LIMIT 5
");

$stok = mysqli_query($conn,"
    SELECT nama, stok
    FROM produk
    ORDER BY id DESC
    LIMIT 5
");

$totalQuery = mysqli_query($conn,"
    SELECT SUM(qty) as total FROM detail_transaksi
");
$totalTerjual = mysqli_fetch_assoc($totalQuery)['total'] ?? 0;

/* ================= DOWNLOAD ================= */
if(isset($_GET['download'])){
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$_GET['download'].'.csv"');
    $output = fopen("php://output","w");

    if($_GET['download']=="penjualan"){
        fputcsv($output,['ID','Tanggal','Pembeli','Total']);
        $data=mysqli_query($conn,"
            SELECT transaksi.id, transaksi.tanggal, users.name, transaksi.total
            FROM transaksi
            JOIN users ON transaksi.user_id=users.id
        ");
        while($row=mysqli_fetch_assoc($data)){ fputcsv($output,$row); }
    }

    if($_GET['download']=="stok"){
        fputcsv($output,['Produk','Stok']);
        $data=mysqli_query($conn,"SELECT nama,stok FROM produk");
        while($row=mysqli_fetch_assoc($data)){ fputcsv($output,$row); }
    }

    if($_GET['download']=="grafik"){
        fputcsv($output,['Tanggal','Total']);
        $data=mysqli_query($conn,"
            SELECT DATE(tanggal) as tanggal, SUM(total) as total
            FROM transaksi
            GROUP BY DATE(tanggal)
        ");
        while($row=mysqli_fetch_assoc($data)){ fputcsv($output,$row); }
    }

    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan - Oxsal Store</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

/* CARD */
.card{
    background:#f3f0ea;
    padding:25px;
    border-radius:15px;
    box-shadow:0 6px 15px rgba(0,0,0,0.08);
    margin-bottom:30px;
}

.card-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.btn-download{
    padding:8px 14px;
    background:#7b5a45;
    color:white;
    border-radius:8px;
    font-size:13px;
    text-decoration:none;
    font-weight:500;
}

.btn-download:hover{
    background:#5c4333;
}

table{width:100%;border-collapse:collapse}
th,td{padding:14px;border-bottom:1px solid #ddd}
th{background:#e5ded5}

.chart-box{
    background:#e5ded5;
    padding:20px;
    border-radius:12px;
}

.total{
    margin-top:15px;
    font-weight:600;
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="produk.php">Produk</a></li>
            <li><a href="user.php">User</a></li>
            <li><a href="laporan.php" class="active">Laporan</a></li>
            <li><a href="pesanan.php">Pesanan</a></li>
        </ul>
    </div>
</div>

<div class="main">

<div class="topbar">
    <h2>Laporan</h2>
    <div class="profile">
        <a href="../logout.php" class="logout-btn">Logout</a>
        <span>Halo, Petugas 👋</span>
        <div class="profile-circle">PS</div>
    </div>
</div>

<!-- PENJUALAN -->
<div class="card">
    <div class="card-header">
        <h3>Ringkasan Penjualan</h3>
        <a href="?download=penjualan" class="btn-download">Download</a>
    </div>
    <table>
        <tr><th>ID</th><th>Tanggal</th><th>Pembeli</th><th>Total</th></tr>
        <?php while($row=mysqli_fetch_assoc($penjualan)): ?>
        <tr>
            <td>#<?= $row['id'] ?></td>
            <td><?= date('d-m-Y',strtotime($row['tanggal'])) ?></td>
            <td><?= $row['pembeli'] ?></td>
            <td>Rp <?= number_format($row['total']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- STOK -->
<div class="card">
    <div class="card-header">
        <h3>Laporan Stok</h3>
        <a href="?download=stok" class="btn-download">Download</a>
    </div>
    <table>
        <tr><th>Produk</th><th>Stok</th></tr>
        <?php while($row=mysqli_fetch_assoc($stok)): ?>
        <tr>
            <td><?= $row['nama'] ?></td>
            <td><?= $row['stok'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- GRAFIK -->
<div class="card">
    <div class="card-header">
        <h3>Grafik Penjualan</h3>
        <a href="?download=grafik" class="btn-download">Download</a>
    </div>

    <div class="chart-box">
        <canvas id="chartPenjualan"></canvas>
    </div>

    <div class="total">
        Total Produk Terjual : <?= number_format($totalTerjual) ?>
    </div>
</div>

</div>

<script>
fetch('chart-data.php')
.then(res=>res.json())
.then(data=>{
new Chart(document.getElementById('chartPenjualan'),{
type:'bar',
data:{
labels:data.labels,
datasets:[{
data:data.data,
backgroundColor:'#7b5a45'
}]
},
options:{plugins:{legend:{display:false}}}
});
});
</script>

</body>
</html>