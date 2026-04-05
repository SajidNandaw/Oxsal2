<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('admin');

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($data);

if(isset($_POST['update'])){
    $nama = $_POST['name'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE users SET 
                        name='$nama',
                        email='$email',
                        status='$status'
                        WHERE id=$id");

    header("Location: user.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit User - Oxsal Store</title>
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

/* CARD FORM */
.card{
    background:#f3f0ea;
    padding:35px;
    border-radius:15px;
    box-shadow:0 6px 15px rgba(0,0,0,0.08);
    max-width:500px;
}

.card h3{
    margin-bottom:25px;
}

/* FORM */
.form-group{
    margin-bottom:20px;
}

label{
    font-size:14px;
    font-weight:500;
    color:#5c4b3f;
}

input, select{
    width:100%;
    padding:12px;
    margin-top:8px;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:14px;
}

input:focus, select:focus{
    outline:none;
    border-color:#7b5a45;
}

/* BUTTON */
.btn-submit{
    width:100%;
    background:#7b5a45;
    color:white;
    padding:12px;
    border:none;
    border-radius:10px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.btn-submit:hover{
    background:#5c4333;
}

.btn-back{
    display:inline-block;
    margin-bottom:20px;
    text-decoration:none;
    color:#5c4b3f;
    font-size:14px;
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
            <li><a href="petugas.php">Petugas</a></li>
            <li><a href="backupdata.php">Backup</a></li>
            <li><a href="user.php" class="active">User</a></li>
            <li><a href="laporan.php">Laporan</a></li>
        </ul>
    </div>
</div>

<div class="main">

<div class="topbar">
    <h2>Edit User</h2>
    <div class="profile">
        <a href="../logout.php" class="logout-btn">Logout</a>
        <span>Halo, Admin 👋</span>
        <div class="profile-circle">AD</div>
    </div>
</div>

<div class="card">

<a href="user.php" class="btn-back">← Kembali ke Manajemen User</a>

<h3>Form Edit User</h3>

<form method="POST">

    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="name" value="<?= $user['name']; ?>" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= $user['email']; ?>" required>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status">
            <option value="aktif" <?= $user['status']=='aktif'?'selected':''; ?>>Aktif</option>
            <option value="nonaktif" <?= $user['status']=='nonaktif'?'selected':''; ?>>Nonaktif</option>
        </select>
    </div>

    <button type="submit" name="update" class="btn-submit">
        Update User
    </button>

</form>

</div>

</div>
</body>
</html>