<?php
require_once "../../middleware/auth.php";
require_once __DIR__ . "/../../config/database.php";

checkRole('petugas');

$query = mysqli_query($conn,"
SELECT transaksi.*, users.name 
FROM transaksi
LEFT JOIN users ON transaksi.user_id = users.id
ORDER BY transaksi.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pesanan - Oxsal Store</title>
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

/* TABLE BOX */
.table-box{
    background:#f3f0ea;
    padding:25px;
    border-radius:15px;
    box-shadow:0 6px 15px rgba(0,0,0,0.08);
}

table{width:100%;border-collapse:collapse}
th,td{padding:14px;border-bottom:1px solid #ddd}
th{background:#e5ded5}

/* STATUS */
.status{
    padding:6px 12px;
    border-radius:10px;
    font-size:12px;
    font-weight:600;
    color:#fff;
}

.menunggu{ background:#d4a373; }
.dibayar{ background:#6a994e; }
.diproses{ background:#bc6c25; }
.dikirim{ background:#577590; }
.selesai{ background:#386641; }

/* BUTTON */
.btn{
    padding:6px 12px;
    border:none;
    border-radius:8px;
    font-size:12px;
    cursor:pointer;
    margin:2px;
    color:white;
}

.btn-detail{ background:#6c757d; }
.btn-kirim{ background:#a67c52; }
.btn-selesai{ background:#7b5a45; }

.btn:hover{opacity:0.85}

form{display:inline;}
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
            <li><a href="laporan.php">Laporan</a></li>
            <li><a href="pesanan.php" class="active">Pesanan</a></li>
        </ul>
    </div>
</div>

<div class="main">

<div class="topbar">
    <h2>Pesanan</h2>
    <div class="profile">
        <a href="../logout.php" class="logout-btn">Logout</a>
        <span>Halo, Petugas 👋</span>
        <div class="profile-circle">PS</div>
    </div>
</div>

<div class="table-box">
<h3>Kelola Pesanan</h3>

<table>
<tr>
<th>ID</th>
<th>User</th>
<th>Total</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php while($p = mysqli_fetch_assoc($query)): ?>

<?php
$status = $p['status'];
$metode = $p['metode'] ?? 'cod';

if(!$status){
    $status = ($metode == 'cod') ? 'diproses' : 'menunggu';
}
?>

<tr>

<td>#<?= $p['id'] ?></td>
<td><?= $p['name'] ?? '-' ?></td>
<td>Rp <?= number_format($p['total']) ?></td>

<td>
<span class="status <?= $status ?>">
<?= ucfirst($status) ?>
</span>
</td>

<td>

<button onclick="window.location.href='detail_pesanan.php?id=<?= $p['id'] ?>'" class="btn btn-detail">
Detail
</button>

<?php if($metode == 'cod'): ?>

    <?php if($status == 'diproses'): ?>
    <form action="kirim.php" method="POST">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">
        <button class="btn btn-kirim">Kirim</button>
    </form>
    <?php endif; ?>

<?php else: ?>

    <?php if($status == 'menunggu'): ?>
    <form action="bayar.php" method="POST">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">
        <button class="btn btn-kirim">Verifikasi</button>
    </form>
    <?php endif; ?>

    <?php if($status == 'dibayar'): ?>
    <form action="kirim.php" method="POST">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">
        <button class="btn btn-kirim">Kirim</button>
    </form>
    <?php endif; ?>

<?php endif; ?>

<?php if($status == 'dikirim'): ?>
<form action="selesai.php" method="POST">
    <input type="hidden" name="id" value="<?= $p['id'] ?>">
    <button class="btn btn-selesai">Selesai</button>
</form>
<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>