<?php
session_start();
require_once "../../config/database.php";

if(!isset($_SESSION['id'])){
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['id'];

if(isset($_GET['id'])){

    $produk_id = intval($_GET['id']);

    // cek apakah sudah ada di keranjang
    $cek = mysqli_query($conn,"
    SELECT * FROM keranjang 
    WHERE user_id='$user_id' AND produk_id='$produk_id'
    ");

    if(mysqli_num_rows($cek) > 0){

        // kalau sudah ada → tambah jumlah
        mysqli_query($conn,"
        UPDATE keranjang 
        SET jumlah = jumlah + 1 
        WHERE user_id='$user_id' AND produk_id='$produk_id'
        ");

    } else {

        // kalau belum → insert baru
        mysqli_query($conn,"
        INSERT INTO keranjang (user_id, produk_id, jumlah)
        VALUES ('$user_id', '$produk_id', 1)
        ");
    }

    header("Location: keranjang.php");
    exit;
} else {
    header("Location: dashboard.php");
    exit;
}