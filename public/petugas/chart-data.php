<?php
require_once "../../config/database.php";

$data = mysqli_query($conn,"
SELECT DATE(tanggal) as tgl, SUM(total) as total 
FROM transaksi
GROUP BY DATE(tanggal)
ORDER BY tgl ASC
");

$labels=[];
$values=[];

while($row=mysqli_fetch_assoc($data)){
    $labels[]=$row['tgl'];
    $values[]=$row['total'];
}

echo json_encode([
    "labels"=>$labels,
    "data"=>$values
]);