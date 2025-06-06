<?php
include '../../service/auth.php';
cekRole('guru'); // hanya guru yang bisa masuk

include '../../service/koneksi.php';

$id_user = (int) $_SESSION['id_user'];

$sql = "SELECT * FROM kelas WHERE id_user = $id_user ORDER BY id_kelas DESC";
$result = mysqli_query($koneksi, $sql);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Beranda</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Jomhuria&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Jomhuria', cursive;
    }

    body {
      background-image: url('../../gambar/Background.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      font-family: 'Jomhuria', cursive;
      height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
      transition: opacity 0.5s ease;
    }

    .topbar {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      background-color: #f7ecd9;
      border-bottom: 1px solid #d4c0a5;
    }

    .topbar-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo {
      width: 60px;
      height: 55px;
      background-image: url('../../gambar/Capybara.png');
      background-size: cover;
    }

    .app-title {
      font-size: 50px;
      font-weight: bold;
      letter-spacing: 2px;
      margin-top: 12px;
      font-family: 'Jomhuria', cursive;
    }

    .subtitle {
      font-size: 28px;
      color: #333;
      margin-top: -13px;
      line-height: 1;
      font-family: 'Jomhuria', cursive;
    }

    .topbar-icons {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .topbar-icons p,
    .profile-icon {
      font-size: 50px;
      cursor: pointer;
    }

    .profile-icon {
      width: 40px;
      height: 40px;
      background-image: url('../../gambar/Capybara.png');
      background-size: cover;
      background-position: center;
      border-radius: 50%;
      border: 1px solid #000;
    }

    .container {
      display: grid;
      grid-template-columns: auto 1fr;
      flex-grow: 1;
      height: 100%;
      transition: all 0.3s ease;
      overflow: hidden; /* Tambahkan ini */
    }

    .container.sidebar-hidden {
      grid-template-columns: 0 1fr;
    }

    .sidebar {
      background-color: #f3e4ce;
      width: 220px;
      padding: 20px 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
      border-right: 1px solid #d4c0a5;
      transition: transform 0.3s ease;
      transform: translateX(0);
    }

    .sidebar.hidden {
      transform: translateX(-100%);
    }

    .sidebar button {
      width: 100%;
      font-size: 25px;
      background-color: #e3d0b7;
      border: none;
      padding: 8px;
      border-radius: 20px;
      cursor: pointer;
      font-family: 'Jomhuria', cursive;
    }

    .sidebar button:hover {
      background-color: #d4b999;
    }


    #menuToggle {
      font-size: 35px;
      cursor: pointer;
      margin-right: 10px;
    }

    .main-content {
      flex-grow: 1;
      padding: 30px;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 30px;
      overflow-y: auto;
    }

    .card {
      max-height: 300px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      background-color: #fff7ed;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s;
      position: relative;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-avatar {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      width: 80px;
      height: 80px;
      background-size: 110px;
      background-position: center;
      border-radius: 50%;
      border: 2px solid #fff;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
      z-index: 2;
    }

    .card img.cover {
      width: 100%;
      height: 150px;
      object-fit: cover;
      background-color: #e0d4c6;
    }

    .card .info {
      padding: 15px;

    }

    .card .info p {
      font-size: 20px;
      color: #5a4b42;
      margin-bottom: 4px;
    }

    .card .info h3 {
      margin-top: 20px; 
      font-size: 30px;
      font-family: 'Jomhuria', cursive;
      letter-spacing: 1.2px;
    }

    /* Tambahan baru */
    .sidebar-courses {
      margin-top: 5px;
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;
      padding-bottom: 140px;
    }

    .course-button {
      background-color: #d4c0a5;
      color: #000;
      font-size: 20px;
      text-align: left;
      padding: 10px 20px;
      width: 100%;
      height: 80%;
      border-radius: 40px;
      box-shadow: 2px 4px 6px rgba(0,0,0,0.2);
      font-family: 'Jomhuria', cursive;
      line-height: 1;
      letter-spacing: 1px;

    }

    .course-button:hover {
      background-color: #d4b999;
    }

    .sidebar-divider {
      width: 100%;
      border: none;
      height: 1px;
      background-color: #c9b9a5;
      margin: 10px 0;
    }
    .sub-btn {
      text-decoration: none;
      color: black;
    }

  .tambah-kelas {
  font-size: 30px;
  font-weight: bold;
  color: black;
  text-decoration: none; /* Menghilangkan underline */
  }

    .a-navbar {
      text-decoration: none;
          color: black;
    }

    /* Sembunyikan scrollbar di WebKit browsers */
    .main-content::-webkit-scrollbar {
      width: 0px;
      background: transparent;
    }

    .buat-kelas-btn {
  margin-top: 10px;
  padding: 10px 20px;
  background-color: #6b4f3b;
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 18px;
  cursor: pointer;
}


    
  </style>
</head>
<body style="cursor: url('./../Homes/Asset/cursor.png') 0 0, auto;">
  <div class="topbar">
    <div class="topbar-left">
      <p id="menuToggle">☰</p>
      <div class="logo"></div>
      <div>
        <div class="app-title">Task Well</div>
      <div class="subtitle"><?= $_SESSION['username'] ?></div>
      </div>
    </div>

    <div class="topbar-icons">
      <a href="#" 
   class="tambah-kelas" 
   style="text-decoration: none; color: black; font-weight: bold;" 
   onclick="logout()">Logout</a>

      <a href="simpan_kelas.php" class="tambah-kelas" style="text-decoration: none; color: black; font-weight: bold;" onclick="tampilkanTombol()">+</a>

<!-- Tempat munculnya tombol -->
      <div id="tempat-tombol"></div>

      <div class="profile-icon"></div>
    </div>
  </div>

<div class="container">
    <div class="sidebar" id="sidebar">
      <a href="pet_siswa.html"><div class="pet-image"></div></a> 
      <button> <a href="berandaGuru.php" class="a-navbar">Beranda</a></button>
      <button> <a href="profile.php" class="a-navbar">Profil</a></button>
      <button> <a href="FAQ.php" class="a-navbar">FAQ</a></button>
</div>

    <div class="main-content">
      <?php while ($row = mysqli_fetch_assoc($result)) : ?>
      <div class="card">
        <a href="forum.php?id=<?= (int)$row['id_kelas']; ?>" class="sub-btn" style="text-decoration: none;">
          <div class="card-avatar" style="background-image: url('../../gambar/icon_beranda.png');"></div>
          <img class="cover" src="../../gambar/books.png" alt="Cover Kelas">
          <div class="info">
            <p><?= htmlspecialchars($row['nama_kelas']); ?></p>
            <h3><?= htmlspecialchars($row['nama_mapel']); ?></h3>
          </div>
        </a>
      </div>
    <?php endwhile; ?>

</div>

  <script>
    document.getElementById('menuToggle').addEventListener('click', function () {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('hidden');
    });

    function logout() {
    alert("Anda berhasil logout.");
    setTimeout(function () {
      window.location.href = "../masuk.html";
    }, 1000); // Delay pendek agar alert ditutup dulu
  }
  </script>
</body>
</html>
