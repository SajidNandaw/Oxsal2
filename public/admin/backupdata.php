<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('admin');

$backup = mysqli_query($conn, 
    "SELECT * FROM backup_data 
     WHERE restored_at IS NULL 
     ORDER BY deleted_at DESC");

$history = mysqli_query($conn, 
    "SELECT * FROM backup_data 
     WHERE restored_at IS NOT NULL 
     ORDER BY restored_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Backup Data - Oxsal Store</title>
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

/* CARD */
.card{
    background:#f3f0ea;
    padding:25px;
    border-radius:15px;
    box-shadow:0 6px 15px rgba(0,0,0,0.08);
    margin-bottom:30px;
}

/* TABLE */
table{width:100%;border-collapse:collapse}
th,td{padding:14px;border-bottom:1px solid #ddd}
th{background:#e5ded5}

/* BADGE */
.badge-warning{
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    background:#f8d7da;
    color:#842029;
}

.badge-success{
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    background:#cdebd7;
    color:#0f5132;
}

/* BUTTON */
.btn-restore{
    background:#7b5a45;
    color:white;
    padding:6px 14px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
}

.btn-download{
    background:#e5ded5;
    padding:6px 14px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
    color:black;
}

.btn-restore:hover{background:#5c4333}
.btn-download:hover{background:#c5ab8f}
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
            <li><a href="petugas.php">Petugas</a></li>
            <li><a href="backupdata.php" class="active">Backup</a></li>
            <li><a href="user.php">User</a></li>
            <li><a href="laporan.php">Laporan</a></li>
        </ul>
    </div>
</div>

<div class="main">

<div class="topbar">
    <h2>Backup Data</h2>
    <div class="profile">
        <a href="../logout.php" class="logout-btn">Logout</a>
        <span>Halo, Admin 👋</span>
        <div class="profile-circle">AD</div>
    </div>
</div>

<!-- DATA BELUM DIRESTORE -->
<div class="card">
<h3>Data Belum Direstore</h3>
<table>
<tr>
<th>Tanggal Hapus</th>
<th>Jenis Data</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($backup)) : ?>
<tr>
<td><?= date('d M Y H:i', strtotime($row['deleted_at'])) ?></td>
<td><?= ucfirst($row['table_name']) ?></td>
<td><span class="badge-warning">Belum Direstore</span></td>
<td>
<a href="restore.php?id=<?= $row['id']; ?>" 
   class="btn-restore"
   onclick="return confirm('Restore data ini?')">
   Restore
</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>

<!-- RIWAYAT RESTORE -->
<div class="card">
<h3>Riwayat Restore</h3>
<table>
<tr>
<th>Tanggal Restore</th>
<th>Jenis Data</th>
<th>Status</th>
<th>Download</th>
</tr>

<?php while($row = mysqli_fetch_assoc($history)) : ?>
<tr>
<td><?= date('d M Y H:i', strtotime($row['restored_at'])) ?></td>
<td><?= ucfirst($row['table_name']) ?></td>
<td><span class="badge-success">Sudah Direstore</span></td>
<td>
<a href="download-backup.php?id=<?= $row['id']; ?>" 
   class="btn-download">
   Download
</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>

</div>
</body>
</html>