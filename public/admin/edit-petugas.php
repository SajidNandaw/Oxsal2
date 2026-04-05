<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('admin');

if (!isset($_GET['id'])) {
    header("Location: petugas.php");
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "SELECT * FROM users WHERE id=$id AND role='petugas'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
        alert('Data tidak ditemukan!');
        window.location='petugas.php';
    </script>";
    exit;
}

if (isset($_POST['update'])) {

    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $status   = $_POST['status'];

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND id!=$id");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah digunakan!');</script>";
    } else {

        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            mysqli_query($conn, "
                UPDATE users SET 
                name='$name',
                email='$email',
                password='$hash',
                status='$status'
                WHERE id=$id
            ");
        } else {
            mysqli_query($conn, "
                UPDATE users SET 
                name='$name',
                email='$email',
                status='$status'
                WHERE id=$id
            ");
        }

        echo "<script>
            alert('Data berhasil diperbarui!');
            window.location='petugas.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Petugas - Oxsal Store</title>
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
    padding:30px;
    border-radius:15px;
    box-shadow:0 6px 15px rgba(0,0,0,0.08);
    max-width:600px;
}

label{
    font-weight:600;
    font-size:14px;
    color:#5c4b3f;
}

input, select{
    width:100%;
    padding:12px;
    margin:8px 0 18px;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:14px;
}

small{
    font-size:12px;
    color:#6b5e55;
}

.btn-group{
    display:flex;
    gap:10px;
}

.btn-primary{
    padding:10px 20px;
    background:#7b5a45;
    color:white;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:500;
}

.btn-primary:hover{
    background:#5c4333;
}

.btn-secondary{
    padding:10px 20px;
    background:#b0a79f;
    color:white;
    text-decoration:none;
    border-radius:10px;
}
</style>
</head>

<body>

<!-- SIDEBAR SAMA PERSIS DENGAN DASHBOARD -->
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
    <h2>Edit Petugas</h2>
    <div class="profile">
        <a href="../logout.php" class="logout-btn">Logout</a>
        <span>Halo, Admin 👋</span>
        <div class="profile-circle">AD</div>
    </div>
</div>

<div class="card">

<form method="POST">

<label>Nama</label>
<input type="text" name="name" value="<?= $data['name']; ?>" required>

<label>Email</label>
<input type="email" name="email" value="<?= $data['email']; ?>" required>

<label>Password Baru</label>
<input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti">
<small>Kosongkan jika tidak ingin mengganti password</small>

<label>Status</label>
<select name="status" required>
<option value="aktif" <?= $data['status']=='aktif'?'selected':''; ?>>Aktif</option>
<option value="nonaktif" <?= $data['status']=='nonaktif'?'selected':''; ?>>Nonaktif</option>
</select>

<div class="btn-group">
<button type="submit" name="update" class="btn-primary">Update</button>
<a href="petugas.php" class="btn-secondary">Kembali</a>
</div>

</form>

</div>

</div>
</body>
</html>