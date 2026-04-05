<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";
checkRole('petugas');

if(!isset($_GET['id'])){
    header("Location: user.php");
    exit;
}

$id = intval($_GET['id']);

// Ambil data user dulu
$data = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM users WHERE id=$id")
);

if(!$data){
    header("Location: user.php");
    exit;
}

// Pastikan hanya role user yang bisa dihapus dari halaman ini
if($data['role'] != 'user'){
    echo "<script>
        alert('Hanya role user yang bisa dihapus di halaman ini!');
        window.location='user.php';
    </script>";
    exit;
}

// ================= SIMPAN KE BACKUP =================
$table_name  = 'users';
$original_id = $data['id'];
$data_backup = json_encode($data);
$deleted_by  = $_SESSION['nama']; // pastikan session ini ada

mysqli_query($conn, "INSERT INTO backup_data 
(original_id, table_name, data_backup, deleted_by, deleted_at) 
VALUES 
('$original_id','$table_name','$data_backup','$deleted_by',NOW())");

// ================= HAPUS DATA ASLI =================
mysqli_query($conn, "DELETE FROM users WHERE id=$id");

header("Location: user.php");
exit;
?>