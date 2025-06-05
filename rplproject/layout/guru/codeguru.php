<?php
session_start();

include "../../service/koneksi.php";
if (!isset($_SESSION['id_user'])) {
    die("Anda belum login. Silakan login terlebih dahulu.");
}

$id_user = $_SESSION['id_user'];

$nama_kelas = mysqli_real_escape_string($koneksi, $_POST['nama_kelas']);
$nama_mapel = mysqli_real_escape_string($koneksi, $_POST['nama_mapel']);

function generateKodeKelas($length = 6) {
    return strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $length));
}

// Generate kode unik
do {
    $kode_kelas = generateKodeKelas();
    $result = mysqli_query($koneksi, "SELECT * FROM kelas WHERE kode_kelas = '$kode_kelas'");
} while (mysqli_num_rows($result) > 0);

// Simpan ke database
$insert = mysqli_query($koneksi, "INSERT INTO kelas (nama_kelas, nama_mapel, kode_kelas, id_user) VALUES ('$nama_kelas', '$nama_mapel', '$kode_kelas', '$id_user')");

if (!$insert) {
    die("Gagal menyimpan kelas: " . mysqli_error($koneksi));
}

// Lanjutkan ke HTML seperti yang kamu buat
?>



<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Task Well - Kode Kelas</title>
  <link href="https://fonts.googleapis.com/css2?family=Jomhuria&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Jomhuria', cursive;
    }

    body, html {
      height: 100%;
      width: 100%;
      font-family: 'Jomhuria', cursive;
      overflow: hidden;
      transition: opacity 0.5s ease;
    }

    .blur-background {
      filter: blur(2px);
      -webkit-filter: blur(2px);
      height: 100%;
      overflow: auto;
    }

    /* ====== BACKGROUND TASK WELL ====== */
    .taskwell-wrapper {
      background: linear-gradient(to bottom, #f7ecd9, #e6d4b4);
      height: 100%;
      display: flex;
      flex-direction: column;
      font-family: 'Jomhuria', cursive;
    }

    .topbar {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      border-bottom: 1px solid #d4c0a5;
      background-color: #f7ecd9;
    }

    .topbar-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .menu-icon {
      font-size: 30px;
      cursor: pointer;
      user-select: none;
    }

    .logo {
      width: 60px;
      height: 55px;
      background-image: url('../../gambar/Capybara.png');
      background-size: cover;
    }

    .app-title {
      font-size: 45px;
      font-family: 'Jomhuria', cursive;
      line-height: 1;
    }

    .subtitle {
      font-size: 28px;
      margin-top: -10px;
      color: #333;
      font-family: 'Jomhuria', cursive;
    }

    .topbar-icons {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .topbar-icons p,
    .profile-icon {
      font-size: 30px;
      cursor: pointer;
      font-family: 'Jomhuria', cursive;
    }

    .profile-icon {
      width: 40px;
      height: 40px;
      background-color: #b79c7a;
      border-radius: 50%;
      background-image: url('../../gambar/Capybara.png');
      background-size: cover;
      border: 1px solid #000;
    }

    .container {
      display: flex;
      height: 100%;
      width: 100%;
    }

    .sidebar {
      background-color: #f3e4ce;
      width: 220px;
      padding: 20px 10px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      border-right: 1px solid #d4c0a5;
      align-items: center;
    }

    .sidebar-logo-only {
      width: 80px;
      height: 80px;
      background-image: url('../../gambar/Capybara.png');
      background-size: cover;
      border-radius: 50%;
      margin-bottom: 10px;
    }

    .sidebar button {
      background-color: #e3d0b7;
      border: none;
      padding: 8px 10px;
      border-radius: 20px;
      font-size: 25px;
      cursor: pointer;
      text-align: center;
      transition: background-color 0.2s ease;
      font-family: 'Jomhuria', cursive;
      width: 100%;
    }

    .sidebar button:hover {
      background-color: #d4b999;
    }

    .main-content {
      flex-grow: 1;
      padding: 20px;
    }

    /* ====== POPUP KODE KELAS ====== */
    .popup-container {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #865b3d;
      padding: 40px 30px;
      border-radius: 30px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
      text-align: center;
      width: 600px;
      z-index: 100;
      font-family: 'Jomhuria', cursive;
    }

    .popup-container img {
      width: 160px;
      margin-bottom: -5px;
      margin-top: -85px
    }

    .popup-container p {
      color: #fff;
      font-size: 25px;
      margin-bottom: 25px;
      letter-spacing: 1px; 
    }

    .class-code {
      background-color: white;
      color: #000;
      padding: 15px;
      font-size: 20px;
      border-radius: 10px;
      margin-bottom: 25px;
      font-family: monospace;
    }

    .buttons {
      display: flex;
      justify-content: space-between;
      gap: 20px;
    }

    .btn {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 12px;
      background-color: #e3b893;
      color: #fff;
      font-size: 24px;
      letter-spacing: 1px;
      cursor: pointer;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
      transition: background 0.3s;
    }

    .btn:hover {
      background-color: #d0a076;
    }
  </style>
</head>
<body style="cursor: url('./../Homes/Asset/cursor.png') 0 0, auto;">
  <!-- ==== BACKGROUND BLUR KONTEN ==== -->
  <div class="blur-background">
    <div class="taskwell-wrapper">
      <div class="topbar">
        <div class="topbar-left">
          <p class="menu-icon">☰</p>
          <div class="logo"></div>
          <div>
            <div class="app-title">Task Well</div>
            <div class="subtitle">Guru</div>
          </div>
        </div>
        <div class="topbar-icons">
          <p>+</p>
          <div class="profile-icon"></div>
        </div>
      </div>
      <div class="container">
        <div class="sidebar">
          <button>Beranda</button>
          <button>Profile</button>
          <button>FAQ</button>
        </div>
        <div class="main-content">
          <!-- Konten utama di sini -->
        </div>
      </div>
    </div>
  </div>

  <!-- ==== POPUP DI ATAS BLUR ==== -->
  <div class="popup-container">
    <img src="../../gambar/CapyWisuda2.png" alt="Capybara Wisuda" />
    <p>Task well menemani kamu untuk membuat kelas dan<br>menciptakan kelas yang menyenangkan!</p>
<div class="class-code" id="kode_kelas"><?= $kode_kelas ?></div>

    <div class="buttons">
      <button class="btn" onclick="salinKode()">Salin Kode Kelas</button>
      <button class="btn" onclick="lanjutkan()">Lanjutkan</button>


    </div>
  </div>

  <script>
    function generateKodeKelas(length = 9) {
      const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      let kode = '';
      for (let i = 0; i < length; i++) {
        kode += chars.charAt(Math.floor(Math.random() * chars.length));
      }
      return kode;
    }

    function salinKode() {
      const kode = document.getElementById('kode_kelas').innerText;
      navigator.clipboard.writeText(kode)
        .then(() => alert("Kode kelas berhasil disalin!"))
        .catch(() => alert("Gagal menyalin kode."));
    }

    function lanjutkan() {
    alert("Berhasil membuat kelas!");
    setTimeout(() => {
      window.location.href = 'berandaGuru.php';
    }, 500); // jeda 0.5 detik
  }

   </script>
</body>
</html>
