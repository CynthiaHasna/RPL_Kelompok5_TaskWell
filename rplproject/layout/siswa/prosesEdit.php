<?php
session_start();
include '../koneksi.php';

$id_user = $_SESSION['id_user'];
$id_tugas = $_POST['id_tugas'];
$jawaban = mysqli_real_escape_string($conn, $_POST['jawaban']);

// Cek apakah siswa sudah pernah mengumpulkan
$cek = mysqli_query($conn, "SELECT file FROM pengumpulan WHERE id_tugas = '$id_tugas' AND id_user = '$id_user'");
$lama = mysqli_fetch_assoc($cek);

$file_name = $_FILES['file']['name'];
$file_tmp = $_FILES['file']['tmp_name'];
$upload_path = "../uploads/";

if (!empty($file_name)) {
  // Optional: hapus file lama jika ingin
  if (!empty($lama['file']) && file_exists($upload_path . $lama['file'])) {
    unlink($upload_path . $lama['file']);
  }

  move_uploaded_file($file_tmp, $upload_path . $file_name);

  $query = "UPDATE pengumpulan 
            SET jawaban='$jawaban', file='$file_name', waktu_pengumpulan=NOW()
            WHERE id_tugas='$id_tugas' AND id_user='$id_user'";
} else {
  $query = "UPDATE pengumpulan 
            SET jawaban='$jawaban', waktu_pengumpulan=NOW()
            WHERE id_tugas='$id_tugas' AND id_user='$id_user'";
}

if (mysqli_query($conn, $query)) {
  header("Location: dashboard.php?edit=success");
} else {
  echo "Gagal mengupdate tugas. Silakan coba lagi.";
}
