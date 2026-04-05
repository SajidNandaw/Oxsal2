<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('admin');

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = mysqli_query($conn, "
        SELECT * FROM users 
        WHERE role='petugas' 
        AND (name LIKE '%$search%' OR email LIKE '%$search%')
        ORDER BY id DESC
    ");
} else {
    $query = mysqli_query($conn, "
        SELECT * FROM users 
        WHERE role='petugas' 
        ORDER BY id DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Petugas - Oxsal Store</title>
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
}

/* HEADER ACTION */
.card-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.search-box input{
    padding:10px 15px;
    border-radius:10px;
    border:1px solid #ccc;
    width:250px;
}

.btn-add{
    background:#7b5a45;
    color:white;
    padding:10px 18px;
    border-radius:10px;
    text-decoration:none;
    font-weight:500;
}

.btn-add:hover{
    background:#5c4333;
}

/* TABLE */
table{width:100%;border-collapse:collapse}
th,td{padding:14px;border-bottom:1px solid #ddd}
th{background:#e5ded5}

.badge{
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.badge-active{
    background:#cdebd7;
    color:#0f5132;
}

.badge-nonactive{
    background:#f8d7da;
    color:#842029;
}

.action-buttons{
    display:flex;
    gap:8px;
}

.btn-edit,
.btn-delete{
    width:35px;
    height:35px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:10px;
    text-decoration:none;
}

.btn-edit{background:#e5ded5}
.btn-delete{background:#f8d7da}

.btn-edit:hover{background:#c5ab8f}
.btn-delete:hover{background:#dc3545;color:white}
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
            <li><a href="petugas.php" class="active">Petugas</a></li>
            <li><a href="backupdata.php">Backup</a></li>
            <li><a href="user.php">User</a></li>
            <li><a href="laporan.php">Laporan</a></li>
        </ul>
    </div>
</div>

<div class="main">

<div class="topbar">
    <h2>Manajemen Petugas</h2>
    <div class="profile">
        <a href="../logout.php" class="logout-btn">Logout</a>
        <span>Halo, Admin 👋</span>
        <div class="profile-circle">AD</div>
    </div>
</div>

<div class="card">

<div class="card-header">

    <!-- SEARCH -->
    <form method="GET" class="search-box">
        <input type="text" name="search" placeholder="Cari nama atau email..." value="<?= $search ?>">
    </form>

    <!-- TOMBOL TAMBAH DI KANAN -->
    <a href="tambah-petugas.php" class="btn-add">+ Tambah Petugas</a>

</div>

<table>
<tr>
<th>Nama</th>
<th>Email</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) : ?>
<tr>
<td><?= $row['name']; ?></td>
<td><?= $row['email']; ?></td>
<td>
<?php if($row['status']=='aktif'): ?>
<span class="badge badge-active">Aktif</span>
<?php else: ?>
<span class="badge badge-nonactive">Nonaktif</span>
<?php endif; ?>
</td>
<td>
<div class="action-buttons">
<a href="edit-petugas.php?id=<?= $row['id']; ?>" class="btn-edit">✏️</a>
<a href="hapus-petugas.php?id=<?= $row['id']; ?>" 
   class="btn-delete"
   onclick="return confirm('Yakin ingin menghapus petugas ini?')">
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