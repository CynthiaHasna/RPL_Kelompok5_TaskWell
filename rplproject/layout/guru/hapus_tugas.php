<?php
session_start();
include "../../service/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_tugas'])) {
    $id_tugas = intval($_POST['id_tugas']);
    
    $query = "DELETE FROM tugas WHERE id_tugas = $id_tugas";
    if (mysqli_query($koneksi, $query)) {
        header("Location: forum.php"); // kembali ke halaman utama setelah hapus
        exit;
    } else {
        echo "Gagal menghapus tugas.";
    }
} else {
    echo "Permintaan tidak valid.";
}
?>
