<?php
require_once "../../middleware/auth.php";
require_once __DIR__ . "/../../config/database.php";

checkRole('petugas');

$search = $_GET['search'] ?? '';

if(!empty($search)){
    $search = mysqli_real_escape_string($conn, $search);
    $query = mysqli_query($conn, "SELECT * FROM users 
        WHERE role='user' 
        AND (name LIKE '%$search%' OR email LIKE '%$search%')
        ORDER BY id DESC");
}else{
    $query = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id DESC");
}

if (!$query) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen User - Oxsal Store</title>
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

/* HEADER CARD */
.header-card{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.search-box form{
    width:100%;
}

.search-box input{
    padding:10px 15px;
    border-radius:10px;
    border:1px solid #ccc;
    width:250px;
}

/* TABLE */
table{width:100%;border-collapse:collapse}
th,td{padding:14px;border-bottom:1px solid #ddd}
th{background:#e5ded5}

/* BADGE */
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

/* BUTTON */
.action-buttons{
    display:flex;
    gap:8px;
}

.btn-edit,
.btn-delete{
    padding:6px 12px;
    border-radius:8px;
    text-decoration:none;
    font-size:12px;
    font-weight:500;
}

.btn-edit{
    background:#e5ded5;
    color:black;
}

.btn-delete{
    background:#f8d7da;
    color:#842029;
}

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
            <li><a href="user.php" class="active">User</a></li>
            <li><a href="laporan.php">Laporan</a></li>
            <li><a href="pesanan.php">Pesanan</a></li>
        </ul>
    </div>
</div>

<div class="main">

<div class="topbar">
    <h2>Manajemen User</h2>
    <div class="profile">
        <a href="../logout.php" class="logout-btn">Logout</a>
        <span>Halo, Petugas 👋</span>
        <div class="profile-circle">PT</div>
    </div>
</div>

<div class="card">

<div class="header-card">
    <div class="search-box">
        <form method="GET">
            <input type="text" name="search" placeholder="Cari User..." value="<?= $_GET['search'] ?? '' ?>">
        </form>
    </div>
</div>

<table>
<tr>
<th>Nama</th>
<th>Email</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) : ?>
<tr>
<td><?= $row['name']; ?></td>
<td><?= $row['email']; ?></td>

<td>
<?php if($row['status'] == 'aktif'): ?>
<span class="badge badge-active">Aktif</span>
<?php else: ?>
<span class="badge badge-nonactive">Nonaktif</span>
<?php endif; ?>
</td>

<td>
<div class="action-buttons">
<a href="edit-user.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
<a href="hapus-user.php?id=<?= $row['id']; ?>" 
   class="btn-delete"
   onclick="return confirm('Yakin ingin menghapus user ini?')">
   Hapus
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