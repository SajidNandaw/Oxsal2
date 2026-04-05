<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('admin');

if (!isset($_GET['id'])) {
    header("Location: petugas.php");
    exit;
}

$id = intval($_GET['id']);

// Pastikan benar-benar petugas
$cek = mysqli_query($conn, "SELECT * FROM users WHERE id=$id AND role='petugas'");
$data = mysqli_fetch_assoc($cek);

if (!$data) {
    echo "<script>
        alert('Data tidak ditemukan atau bukan petugas!');
        window.location='petugas.php';
    </script>";
    exit;
}

// ============================
// SIMPAN KE TABEL BACKUP
// ============================

$original_id = $data['id'];
$table_name  = 'users';
$data_backup = mysqli_real_escape_string($conn, json_encode($data));
$deleted_by  = $_SESSION['nama']; // pastikan session ini ada

mysqli_query($conn, "
    INSERT INTO backup_data 
    (original_id, table_name, data_backup, deleted_by, deleted_at)
    VALUES 
    ('$original_id', '$table_name', '$data_backup', '$deleted_by', NOW())
");

// ============================
// HAPUS DARI TABEL USERS
// ============================

mysqli_query($conn, "DELETE FROM users WHERE id=$id");

echo "<script>
    alert('Petugas berhasil dihapus dan dibackup!');
    window.location='petugas.php';
</script>";
?>