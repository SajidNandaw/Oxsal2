<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('admin');

if(!isset($_GET['id'])){
    exit("ID tidak ditemukan");
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "SELECT * FROM backup_data WHERE id=$id");
$data = mysqli_fetch_assoc($query);

if(!$data){
    exit("Data tidak ditemukan");
}

$filename = "backup_" . $data['table_name'] . "_" . date('Ymd_His') . ".json";

header("Content-Type: text/plain");
header("Content-Disposition: attachment; filename=backup_$id.txt");

echo $data['data_backup'];
exit;
?>