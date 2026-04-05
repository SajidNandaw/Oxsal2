<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";
checkRole('admin');

if(!isset($_GET['id'])){
    header("Location: produk.php");
    exit;
}

$id = intval($_GET['id']);

// Ambil data produk dulu
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id=$id"));

if(!$data){
    header("Location: produk.php");
    exit;
}

// Simpan ke tabel backup
$table_name = 'produk';
$original_id = $data['id'];
$data_backup = json_encode($data);
$deleted_by = $_SESSION['nama']; // atau username kamu

mysqli_query($conn, "INSERT INTO backup_data 
(original_id, table_name, data_backup, deleted_by, deleted_at) 
VALUES 
('$original_id','$table_name','$data_backup','$deleted_by',NOW())");

// Hapus data asli
mysqli_query($conn, "DELETE FROM produk WHERE id=$id");

header("Location: produk.php");