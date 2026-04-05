<?php
require_once "../../middleware/auth.php";
require_once __DIR__ . "/../../config/database.php";

checkRole('petugas');

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'");
$row = mysqli_fetch_assoc($data);

if(isset($_POST['update'])){

    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if($_FILES['gambar']['name'] != ""){

        $gambarBaru = time() . "_" . $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];

        if(!empty($row['gambar']) && file_exists("../../uploads/".$row['gambar'])){
            unlink("../../uploads/".$row['gambar']);
        }

        move_uploaded_file($tmp, "../../uploads/".$gambarBaru);

        mysqli_query($conn, "UPDATE produk SET
            nama='$nama',
            deskripsi='$deskripsi',
            harga='$harga',
            stok='$stok',
            gambar='$gambarBaru'
            WHERE id='$id'
        ");

    } else {

        mysqli_query($conn, "UPDATE produk SET
            nama='$nama',
            deskripsi='$deskripsi',
            harga='$harga',
            stok='$stok'
            WHERE id='$id'
        ");
    }

    header("Location: produk.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Produk - OXSAL</title>
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

/* SIDEBAR */

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
}

/* MAIN */

.main{
    margin-left:250px;
    padding:40px;
    width:100%;
}

/* TOPBAR */

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

/* FORM CARD */

.form-card{
    background:#ece9e4;
    padding:35px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    max-width:650px;
}

.form-card h3{
    margin-bottom:25px;
    color:#5c4b3f;
}

/* INPUT STYLE */

label{
    font-weight:500;
    color:#5c4b3f;
    display:block;
    margin-bottom:8px;
}

input, textarea{
    width:100%;
    padding:12px 15px;
    margin-bottom:20px;
    border-radius:12px;
    border:1px solid #d6cfc7;
    background:#f8f6f3;
    font-size:14px;
    outline:none;
}

textarea{
    resize:none;
    height:110px;
}

input:focus, textarea:focus{
    border-color:#b89572;
}

/* IMAGE */

.current-img img{
    width:130px;
    border-radius:12px;
    margin-bottom:15px;
}

/* BUTTONS */

.btn-group{
    display:flex;
    gap:12px;
}

.btn-update{
    padding:12px 25px;
    background:#7b5a45;
    color:white;
    border:none;
    border-radius:12px;
    cursor:pointer;
    font-weight:500;
    transition:0.3s;
}

.btn-update:hover{
    background:#5c4b3f;
}

.btn-back{
    padding:12px 25px;
    background:#b89572;
    color:white;
    border-radius:12px;
    text-decoration:none;
    font-weight:500;
}

.btn-back:hover{
    background:#a07f60;
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
    <h2>Edit Produk</h2>
    <div class="profile">
        <a href="../logout.php" class="logout-btn">Log out</a>
        <span>Halo, Admin</span>
        <div class="profile-circle">AD</div>
    </div>
</div>

<div class="form-card">
    <h3>Form Edit Produk</h3>

    <form method="POST" enctype="multipart/form-data">

        <label>Nama Produk</label>
        <input type="text" name="nama" value="<?= $row['nama']; ?>" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" required><?= $row['deskripsi']; ?></textarea>

        <label>Harga</label>
        <input type="number" name="harga" value="<?= $row['harga']; ?>" required>

        <label>Stok</label>
        <input type="number" name="stok" value="<?= $row['stok']; ?>" required>

        <label>Gambar Saat Ini</label>
        <div class="current-img">
            <?php if(!empty($row['gambar'])): ?>
                <img src="../../uploads/<?= $row['gambar']; ?>">
            <?php else: ?>
                Tidak ada gambar
            <?php endif; ?>
        </div>

        <label>Ganti Gambar (Opsional)</label>
        <input type="file" name="gambar">

        <div class="btn-group">
            <button type="submit" name="update" class="btn-update">Update</button>
            <a href="produk.php" class="btn-back">Kembali</a>
        </div>

    </form>
</div>

</div>
</body>
</html>