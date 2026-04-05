<?php
require_once "../../middleware/auth.php";
require_once __DIR__ . "/../../config/database.php";

checkRole('petugas');

$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
if (!$query) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Produk - OXSAL</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    display:flex;
    background:#d9d4cc;
}

/* ================= SIDEBAR ================= */

.sidebar{
    width:250px;
    background:#f3f0ea;
    height:100vh;
    padding:25px;
    position:fixed;
    border-right:2px solid #e5ded5;
}

.logo{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:30px;
}

.logo img{width:40px;}

.logo-text h2{color:#a67c00;}
.logo-text span{font-size:14px;color:#6b5e55;}

.menu ul{list-style:none;}
.menu ul li{margin-bottom:15px;}

.menu ul li a{
    text-decoration:none;
    color:#5c4b3f;
    padding:12px 15px;
    display:block;
    border-radius:12px;
    transition:0.3s;
    font-weight:500;
}

.menu ul li a:hover,
.menu ul li a.active{
    background:#c5ab8f;
    color:#000;
}

/* ================= MAIN ================= */

.main{
    margin-left:250px;
    padding:40px;
    width:100%;
}

/* ================= TOPBAR ================= */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
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

/* ================= CARD PRODUK ================= */

.product-card{
    background:#ece9e4;
    padding:25px;
    border-radius:18px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

/* HEADER CARD */

.header-card{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.search-box{
    background:#f5f5f5;
    padding:10px 15px;
    border-radius:12px;
    border:1px solid #ddd;
    width:250px;
    outline:none;
}

.btn-add{
    background:#ffffff;
    border:1px solid #ccc;
    padding:10px 18px;
    border-radius:12px;
    text-decoration:none;
    color:#333;
    font-weight:500;
    transition:0.3s;
}

.btn-add:hover{
    background:#ddd;
}

/* ================= TABLE ================= */

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:18px 15px;
    text-align:left;
}

th{
    color:#555;
    font-weight:600;
    border-bottom:2px solid #ddd;
}

tr{
    border-bottom:1px solid #ddd;
}

tr:last-child{
    border-bottom:none;
}

.product-info{
    display:flex;
    align-items:center;
    gap:15px;
}

.product-img{
    width:60px;
    height:60px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid #ccc;
}

/* ACTION BUTTONS */

.action-buttons{
    display:flex;
    gap:8px;
}

.btn-edit{
    background:#f0f0f0;
    width:35px;
    height:35px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:10px;
    text-decoration:none;
    transition:0.3s;
}

.btn-edit:hover{
    background:#c5ab8f;
}

.btn-delete{
    background:#f8d7da;
    width:35px;
    height:35px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:10px;
    text-decoration:none;
    transition:0.3s;
}

.btn-delete:hover{
    background:#dc3545;
    color:white;
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
            <li><a href="produk.php" class="active">Produk</a></li>
            <li><a href="user.php">User</a></li>
            <li><a href="laporan.php">Laporan</a></li>
            <li><a href="pesanan.php">Pesanan</a></li>
        </ul>
    </div>
</div>

<div class="main">

<div class="topbar">
    <h2 style="font-size:28px;">Manajemen Produk</h2>

    <div class="profile">
        <a href="../logout.php" class="logout-btn">Log out</a>
        <span>Halo, Admin</span>
        <div class="profile-circle">AD</div>
    </div>
</div>

<div class="product-card">

    <div class="header-card">
        <input type="text" class="search-box" placeholder="Cari Produk...">
        <a href="tambah-produk.php" class="btn-add">Tambah Produk +</a>
    </div>

    <table>
        <tr>
            <th>Nama Produk</th>
            <th>Stok</th>
            <th>Harga</th>
            <th>Action</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($query)) : ?>
        <tr>
            <td>
                <div class="product-info">
                    <img src="../../uploads/<?= $row['gambar']; ?>" class="product-img">
                    <?= $row['nama']; ?>
                </div>
            </td>
            <td><?= $row['stok']; ?></td>
            <td>Rp <?= number_format($row['harga']); ?></td>
            <td>
                <div class="action-buttons">
                    <a href="edit-produk.php?id=<?= $row['id']; ?>" class="btn-edit">✏️</a>

                    <a href="hapus-produk.php?id=<?= $row['id']; ?>" 
                       class="btn-delete"
                       onclick="return confirm('Yakin ingin menghapus produk ini?')">
                       🗑️
                    </a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>

</div>

</div>

</body>
</html>