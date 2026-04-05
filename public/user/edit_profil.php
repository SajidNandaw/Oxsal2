<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('user');

$user_id = $_SESSION['id'];

// 🔥 CEK KOLOM ALAMAT
$check = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'alamat'");
$ada_alamat = mysqli_num_rows($check) > 0;

// AMBIL DATA USER
$query = mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query) ?? [];

if(isset($_POST['update'])){

$name   = $_POST['name'];
$email  = $_POST['email'];
$alamat = $_POST['alamat'] ?? '';

if($ada_alamat){

    $stmt = mysqli_prepare($conn, "
    UPDATE users 
    SET name=?, email=?, alamat=? 
    WHERE id=?
    ");

    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $alamat, $user_id);

}else{

    $stmt = mysqli_prepare($conn, "
    UPDATE users 
    SET name=?, email=? 
    WHERE id=?
    ");

    mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $user_id);

}

mysqli_stmt_execute($stmt);

// REDIRECT
echo "<script>
alert('Profil berhasil diupdate');
window.location.href='profile.php';
</script>";
exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Profil - Oxsal Store</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#efe7de;
}

/* NAVBAR */
header{
background:#7b5e4a;
padding:15px 50px;
display:flex;
align-items:center;
gap:12px;
color:white;
font-weight:600;
font-size:20px;
}

.logo-img{
height:40px;
}

/* CONTAINER */
.container{
width:420px;
margin:auto;
margin-top:80px;
}

/* BACK */
.back{
display:inline-block;
margin-bottom:20px;
text-decoration:none;
color:black;
background:#d8c2a8;
padding:8px 18px;
border-radius:20px;
font-weight:500;
}

/* CARD */
.card{
background:#ffffff;
padding:30px;
border-radius:20px;
box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

/* TITLE */
.title{
font-size:22px;
font-weight:600;
margin-bottom:20px;
text-align:center;
}

/* FORM */
label{
font-size:14px;
font-weight:500;
}

input, textarea{
width:100%;
padding:12px;
margin-top:6px;
margin-bottom:15px;
border-radius:10px;
border:1px solid #ddd;
outline:none;
background:#f9f9f9;
}

input:focus, textarea:focus{
border:1px solid #c6a481;
}

textarea{
resize:none;
height:90px;
}

/* BUTTON */
button{
width:100%;
padding:12px;
background:#c6a481;
color:white;
border:none;
border-radius:12px;
cursor:pointer;
font-weight:600;
transition:0.2s;
}

button:hover{
opacity:0.9;
transform:scale(0.98);
}

/* FOOTER */
.footer-bottom{
background:#7b5e4a;
color:white;
text-align:center;
padding:15px;
margin-top:100px;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<header>
    <img src="../../assets/oxsal.png" class="logo-img">
    OXSAL STORE
</header>

<div class="container">

<a href="profile.php" class="back">← Kembali</a>

<div class="card">

<div class="title">Edit Profil</div>

<form method="POST">

<label>Nama</label>
<input type="text" name="name" value="<?= $user['name'] ?? '' ?>" required>

<label>Email</label>
<input type="email" name="email" value="<?= $user['email'] ?? '' ?>" required>

<label>Alamat</label>
<textarea name="alamat"><?= $user['alamat'] ?? '' ?></textarea>

<button name="update">Simpan Perubahan</button>

</form>

</div>

</div>

<div class="footer-bottom">
© Oxsal Store 2026. All Rights Reserved
</div>

</body>
</html>