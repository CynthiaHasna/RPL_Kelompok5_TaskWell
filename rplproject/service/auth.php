<?php
session_start();

function cekRole($roleDibolehkan) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $roleDibolehkan) {
        header("Location: ../unauthorized.php"); // halaman gagal akses
        exit();
    }
}
?>
