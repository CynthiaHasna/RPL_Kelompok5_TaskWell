<?php
session_start();
include '../../service/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
  header("Location: ../layout/login.php");
  exit;
}
// Cek role (pastikan ini hanya bisa diakses oleh siswa)
if ($_SESSION['role'] !== 'siswa') {
  echo "Akses ditolak. Halaman ini hanya untuk siswa.";
  exit;
}

// Ambil ID user siswa dari session
$id_user = $_SESSION['id_user'];

// Ambil kelas yang diikuti siswa
$sql = "SELECT k.* 
        FROM kelas k
        JOIN kelas_anggota ka ON k.id_kelas = ka.id_kelas
        WHERE ka.id_user = $id_user
        ORDER BY k.id_kelas DESC";

$result = mysqli_query($koneksi, $sql);

if (!$result) {
  die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
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
    }

    body {
      font-family: 'Inter', sans-serif;
      display: flex;
      flex-direction: column;
      height: 100vh;
      background-image: url('../../gambar/Background.png');
      overflow: auto;
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

    .a-navbar {
  color: black;
  text-decoration: none;
  display: center; /* Agar memenuhi tombol */
  width: 100%;
  height: 100%;
}


  button {
    background-color: transparent;
    border: none;
    padding: 10px 0;
    width: 100%;
    color: #000000;
    cursor: pointer;
justify-content: center;  
    display: flex;
    align-items: center;      /* ini buat vertikal center */
  text-align: center; 

}

  button:hover {
    background-color: #495057;
  }

    .pet-image {
      width: 120px;
      height: 120px;
      background-image: url('../../gambar/Capybara2.png');
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
      padding: 2px;
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
      font-size: 13px;
      color: #5a4b42;
      margin-bottom: 4px;
    }

    .card .info h3 {
  margin-top: 20px;
  font-size: 30px;
  font-family: 'Jomhuria', cursive;
  letter-spacing: 1.2px;
  color: black; /* Menjadikan teks hitam */
  text-decoration: none; /* Menghilangkan underline */
}


    /* Tambahan baru */
    .sidebar-courses {
      margin-top: 2px;
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;
      padding-bottom: 20px;
    }

    .course-button {
      background-color: #e3d0b7;
      color: #000;
      font-size: 10px;
      text-align: left;
      padding: 10px 20px;
      width: 100%;
      height: 80%;
      border-radius: 40px;
      font-family: 'Poppins', sans-serif;
      line-height: 1.3;
    }

    .course-button:hover {
  background-color: #d4b999;  /* warna berubah saat hover  */
}
    .sidebar-divider {
  width: 100%;
  border: none;
  border-bottom: 1px solid rgba(0,0,0,0.3);
  margin: 10px 0;
  height: 0;
  display: block;
}

.container.sidebar-hidden {
  grid-template-columns: 0 1fr;
}

.pet-image {
  width: 120px;
  height: 120px;
  background-image: url('../../gambar/petSick.png');
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
  padding: 2px;
  transition: transform 0.3s ease; /* tambahkan transisi agar animasi halus */
}

.pet-image:hover {
  transform: scale(1.1) rotate(5deg); /* zoom 110% dan rotate 5 derajat */
  cursor: pointer; /* agar kursor berubah saat hover */
}

.wiggle {
  animation: floatWiggle 3s ease-in-out infinite;
  transform-origin: center;
}

@keyframes floatWiggle {
  0% {
    transform: translateY(0) rotate(0deg);
  }
  25% {
    transform: translateY(-5px) rotate(3deg);
  }
  50% {
    transform: translateY(-10px) rotate(0deg);
  }
  75% {
    transform: translateY(-5px) rotate(-3deg);
  }
  100% {
    transform: translateY(0) rotate(0deg);
  }
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
      <div class="subtitle">Halo, <?= $_SESSION['username'] ?></div>
      </div>
    </div>

    <div class="topbar-icons">
    <a href="#" 
   class="tambah-kelas" 
   style="text-decoration: none; color: black; font-weight: bold;" 
   onclick="logout()">Logout</a>
      <a href="PBKelas.php" class="tambah-kelas" style="text-decoration: none; font-size: 40px; color: black; font-weight: bold;" onclick="tampilkanTombol()">+</a>
      <div class="profile-icon"></div>
    </div>
  </div>

  <div class="container">
    <div class="sidebar" id="sidebar">
      <a href="pet_siswa.html"><div class="pet-image"></div></a> 
      <button> <a href="Beranda_siswa.php" class="a-navbar">Beranda</a></button>
      <button> <a href="forumsiswa.php" class="a-navbar">Tingkatan Tugas</a></button>
      <button> <a href="profil_siswa.php" class="a-navbar">Profil</a></button>
      <button> <a href="FAQ.php" class="a-navbar">FAQ</a></button>
</div>

<div class="main-content">
  <?php if (mysqli_num_rows($result) === 0): ?>
    <div style="grid-column: 1 / -1; text-align: center; margin-top: 100px;">
      <img src="../../gambar/capybarasiswa.png" alt="Belum ada kelas" class="wiggle" style="max-width: 300px; width: 100%; margin-bottom: 20px;">
      <h2 style="font-family: 'Poppins', sans-serif; color: #5a4b42;">Kamu belum mengikuti kelas</h2>
      <p style="font-family: 'Poppins', sans-serif; font-size: 18px;">Yuk masuk ke kelasmu!</p>
    </div>
  <?php else: ?>
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
      <div class="card">
        <a href="forumsiswa.php?id=<?= (int)$row['id_kelas']; ?>" class="sub-btn" style="text-decoration: none;">
          <div class="card-avatar" style="background-image: url('../../gambar/icon_beranda.png');"></div>
          <img class="cover" src="../../gambar/books.png" alt="Cover Kelas">
          <div class="info">
            <p><?= htmlspecialchars($row['nama_mapel']); ?></p>
            <h3><?= htmlspecialchars($row['nama_kelas']); ?></h3>
          </div>
        </a>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>



  <script>
  document.getElementById('menuToggle').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    const container = document.querySelector('.container');
    sidebar.classList.toggle('hidden');
    container.classList.toggle('sidebar-hidden');
  });

  function logout() {
    alert("Anda berhasil logout.");
    setTimeout(function () {
      window.location.href = "./../masuk.html";
    }, 1000); // Delay pendek agar alert ditutup dulu
  }

</script>

</body>
</html>
