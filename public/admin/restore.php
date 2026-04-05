<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('admin');

if(!isset($_GET['id'])){
    header("Location: backupdata.php");
    exit;
}

$id = intval($_GET['id']);

// Ambil data backup
$query = mysqli_query($conn, "SELECT * FROM backup_data WHERE id=$id");
$data = mysqli_fetch_assoc($query);

if(!$data){
    echo "Data tidak ditemukan!";
    exit;
}

// Cek kalau sudah direstore
if($data['restored_at'] != NULL){
    echo "<script>
        alert('Data sudah pernah direstore!');
        window.location='backupdata.php';
    </script>";
    exit;
}

// Decode JSON
$originalData = json_decode($data['data_backup'], true);

$table = $data['table_name'];

// =======================
// INSERT KEMBALI DATA
// =======================

$columns = implode(", ", array_keys($originalData));
$values  = implode("', '", array_map(function($v){
    return addslashes($v);
}, array_values($originalData)));

mysqli_query($conn, "
    INSERT INTO $table ($columns) 
    VALUES ('$values')
");

// =======================
// UPDATE STATUS RESTORE
// =======================

mysqli_query($conn, "
    UPDATE backup_data 
    SET restored_at = NOW() 
    WHERE id = $id
");

// =======================

echo "<script>
    alert('Data berhasil direstore!');
    window.location='backupdata.php';
</script>";
?>