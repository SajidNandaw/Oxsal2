<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "nan_store";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
