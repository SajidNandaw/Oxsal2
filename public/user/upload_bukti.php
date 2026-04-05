<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('user');

if(!isset($_SESSION['id'])){
    header("Location: ../../login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $transaksi_id = $_POST['transaksi_id'] ?? 0;

    if(!$transaksi_id){
        die("ID transaksi tidak valid");
    }

    if(!isset($_FILES['bukti'])){
        die("File tidak ditemukan");
    }

    $file = $_FILES['bukti'];

    if($file['error'] != 0){
        die("Upload gagal");
    }

    /* ================= VALIDASI ================= */
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png'];

    if(!in_array($ext, $allowed)){
        die("Format harus JPG / PNG");
    }

    /* ================= BUAT FOLDER ================= */
    if(!is_dir("../../uploads")){
        mkdir("../../uploads", 0777, true);
    }

    /* ================= UPLOAD ================= */
    $nama_file = "bukti_" . time() . "." . $ext;
    $path = "../../uploads/" . $nama_file;

    move_uploaded_file($file['tmp_name'], $path);

    /* ================= CEK KOLOM BUKTI ================= */
    $cekKolom = mysqli_query($conn, "
        SHOW COLUMNS FROM transaksi LIKE 'bukti'
    ");

    if(mysqli_num_rows($cekKolom) > 0){
        // kalau kolom ADA
        $query = "
        UPDATE transaksi
        SET bukti='$nama_file', status='menunggu_verifikasi'
        WHERE id='$transaksi_id'
        ";
    }else{
        // kalau kolom TIDAK ADA
        $query = "
        UPDATE transaksi
        SET status='menunggu_verifikasi'
        WHERE id='$transaksi_id'
        ";
    }

    $update = mysqli_query($conn, $query);

    if(!$update){
        die("Query error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembayaran Berhasil</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#f5f1ec;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* POPUP */
.popup{
    background:#d2b48c; /* Sama dengan warna card di dashboard */
    padding:40px;
    border-radius:15px;
    text-align:center;
    width:350px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    color:#fff;
}

.icon{
    font-size:60px;
    margin-bottom:15px;
}

h2{
    font-size:22px;
    margin-bottom:10px;
}

p{
    font-size:16px;
    margin-bottom:20px;
}

button{
    background:#6f4e37; /* Sama dengan tombol hero di dashboard */
    color:white;
    border:none;
    padding:12px 25px;
    border-radius:25px;
    cursor:pointer;
    font-weight:500;
    transition:0.2s;
}

button:hover{
    background:#563a29; /* versi gelap saat hover */
}
</style>
</head>
<body>

<div class="popup">
    <div class="icon">✅</div>
    <h2>Pembayaran Berhasil</h2>
    <p>Bukti pembayaran berhasil dikirim</p>

    <button onclick="goDashboard()">Kembali</button>
</div>

<script>
function goDashboard(){
    window.location.href="dashboard.php";
}

setTimeout(()=>{
    window.location.href="dashboard.php";
},3000);
</script>

</body>
</html>