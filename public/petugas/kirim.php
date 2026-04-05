<?php
require_once "../../config/database.php";

$id = $_POST['id'];
mysqli_query($conn,"UPDATE transaksi SET status='dikirim' WHERE id='$id'");

header("Location: pesanan.php");