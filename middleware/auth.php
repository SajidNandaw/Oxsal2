<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| CEK LOGIN
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| CEK STATUS AKUN (AKTIF / NONAKTIF)
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'aktif') {
    // Hancurkan session kalau status tidak aktif
    session_destroy();
    header("Location: ../login.php?error=akun_nonaktif");
    exit;
}

/*
|--------------------------------------------------------------------------
| FUNCTION CEK ROLE (SUPPORT MULTIPLE ROLE)
|--------------------------------------------------------------------------
*/
function checkRole($roles)
{
    if (!isset($_SESSION['role'])) {
        header("Location: ../login.php");
        exit;
    }

    // Ubah jadi array kalau hanya 1 role
    if (!is_array($roles)) {
        $roles = [$roles];
    }

    // Jika role tidak ada dalam daftar yang diizinkan
    if (!in_array($_SESSION['role'], $roles)) {
        header("Location: ../forbidden.php");
        exit;
    }
}