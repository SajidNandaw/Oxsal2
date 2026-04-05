<?php
require_once "../../middleware/auth.php";
require_once "../../config/database.php";

checkRole('petugas');

if(!isset($_POST['id'])){
    header("Location: pesanan.php");
    exit;
}

$id = intval($_POST['id']);

mysqli_query($conn, "UPDATE transaksi SET status='selesai' WHERE id='$id'");

mysqli_query($conn, "
UPDATE produk p
JOIN detail_transaksi d ON p.id = d.produk_id
SET p.terjual = p.terjual + d.qty
WHERE d.transaksi_id = '$id'
");

header("Location: pesanan.php");
exit;
?>