<?php
session_start();
include "../../service/koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['id_user']) || !isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}

// Jika ada request id_tugas, simpan ke session dan redirect ke halaman submit
if (isset($_GET['id_tugas'])) {
    $_SESSION['id_tugas'] = (int)$_GET['id_tugas'];
    header("Location: SubmitTugas.php");
    exit();
}

// Ambil ID user dari session
$id_user = intval($_SESSION['id_user']);

// Query untuk mengambil semua tugas beserta nama kelas
$query = "
  SELECT tugas.*, kelas.nama_kelas 
  FROM tugas 
  LEFT JOIN kelas ON tugas.id_kelas = kelas.id_kelas 
  ORDER BY tugas.created_at DESC
";

$result = mysqli_query($koneksi, $query);

$tugas_list = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tugas_list[] = $row;
    }
} else {
    $tugas_list = [];
}

$submitted_tasks = [];
$query_cek = "SELECT id_tugas FROM pengumpulan WHERE id_user = $id_user";
$result_cek = mysqli_query($koneksi, $query_cek);
if ($result_cek && mysqli_num_rows($result_cek) > 0) {
    while ($row = mysqli_fetch_assoc($result_cek)) {
        $submitted_tasks[] = $row['id_tugas'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ListTugas</title>
  <link href="https://fonts.googleapis.com/css2?family=Jomhuria&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="forumalpro.css" />

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
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
      margin-top: 13px;
    }

    .subtitle {
      font-size: 28px;
      color: #333;
      margin-top: -13px;
      line-height: 1;
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

    #menuToggle {
      font-size: 35px;
      cursor: pointer;
      margin-right: 10px;
    }

    .container {
      display: grid;
      grid-template-columns: auto 1fr;
      flex-grow: 1;
      transition: all 0.3s ease;
      height: 100%;
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

    .main-content {
      flex-grow: 1;
      padding: 30px;
      overflow-y: auto;
    }

    .overlay {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .task-container {
      font-family: 'Poppins', sans-serif;
      max-width: 850px;
      width: 90%;
    }

    .tabs {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 25px;
      flex-wrap: wrap;
    }

    .tabs span {
      padding: 8px 20px;
      border-radius: 12px;
      font-weight: 600;
      cursor: pointer;
      color: #000000;
      transition: 0.3s;
    }

    .tabs a {
      /* meniru gaya dari span */
      display: inline-block;
      padding: 10px;
      color: black;
      text-decoration: none;
    }

    .tabs a.active {
      /* gaya tab aktif */
      font-weight: bold;
      border-bottom: 2px solid #000;
    }


    .tabs span.active {
      background-color: #e0c3a3;
      border: 1px solid #a07e56;
      color: #3f3f3f;
    }

    .header-image-section {
      position: relative;
      height: 180px;
      margin-bottom: 20px;
      border-radius: 10px;
      overflow: hidden;
    }

    .header-bg {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .header-text {
      position: absolute;
      bottom: 20px;
      left: 30px;
      color: white;
      text-shadow: 1px 1px 4px rgba(0,0,0,0.7);
    }

    .header-text h2 {
      margin: 0;
      font-size: 32px;
      font-weight: bold;
    }

    .header-text p {
      margin: 0;
      font-size: 20px;
    }

    .task-wrapper {
      display: flex;
      gap: 20px;
      align-items: flex-start;
      flex-wrap: wrap;
    }

    .task-highlight-box {
      display: flex;
      align-items: center;
      background-color: #E1D2BE;
      border-radius: 12px;
      padding: 25px;
      width: 260px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
      gap: 10px;
      justify-content: center;
      border: 2px solid #000;
    }

    .task-highlight-box img {
      width: 80px;
      height: 80px;
      object-fit: contain;
      display: flex;
      justify-content: center;
    }
    .status-label {
  display: inline-block;
  margin-top: 10px;
  padding: 4px 10px;
  border-radius: 15px;
  font-size: 12px;
  font-weight: bold;
  color: white;
}

.status-label.done {
  background-color: #4ade80; /* hijau */
}

.status-label.not-done {
  background-color: #f87171; /* merah */
}


    .task-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
    }

    .task-title {
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      font-weight: 600;
      margin: 0;
    }

    .pdf-button {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background-color: #856451;
      color: white;
      font-size: 14px;
      text-decoration: none;
      padding: 4px 10px;
      border-radius: 6px;
    }

    .pdf-button img {
      width: 20px;
      height: auto;
    }

    .task-list {
      display: flex;
      flex-direction: column;
      gap: 16px;
      flex: 1;
    }

    .task-item {
      display: flex;
      gap: 12px;
      background-color: #e1d2be;
      padding: 16px;
      border-radius: 16px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      border: 2px solid #000;
      align-items: flex-start;
      font-family: 'Poppins', sans-serif;
    }

    .task-body {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .task-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .task-status {
      background-color: #856451;
      color: white;
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
    }

    .task-card {
      display: flex;
      background-color: #fff;
      border-radius: 12px;
      padding: 12px;
      align-items: center;
      gap: 12px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    .pdf-icon {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    .task-desc {
      display: flex;
      flex-direction: column;
      width: 100%;
      overflow: hidden;
    }

    .task-title {
      font-size: 13px;
      font-weight: 600;
      margin: 0;
    }

    .task-subtitle {
      font-size: 12px;
      color: #555;
      margin: 0;
    }

    .comment-box {
      width: 100%;
      padding: 8px 12px;
      border-radius: 12px;
      border: 1px solid #ccc;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
    }

    @media (max-width: 768px) {
      .topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }

      .app-title {
        font-size: 36px;
      }

      .subtitle {
        font-size: 18px;
      }

      .topbar-icons {
        align-self: flex-end;
        margin-right: 10px;
      }

      .container {
        grid-template-columns: 1fr;
      }

      .sidebar {
        position: absolute;
        top: 65px;
        left: 0;
        height: calc(100% - 65px);
        z-index: 1000;
        background-color: #fff6e9;
        transform: translateX(-100%);
      }

      .sidebar.visible {
        transform: translateX(0);
      }

      .main-content {
        padding: 16px;
      }

      .task-wrapper {
        flex-direction: column;
        align-items: center;
      }

      .task-highlight-box, .task-list {
        width: 100%;
      }

      .task-item {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }
    
      .task-content {
        align-items: center;
      }
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
    .a-navbar {
      text-decoration: none;
          color: black;
    }
   
    .send-button{
        background-color:rgb(253, 253, 253);
        color: #000;
        font-size: 20px;
        text-align: center;
        padding: 5px 5px;
        width: 100%;
        height: 80%;
        border-radius: 40px;
        box-shadow: 2px 4px 6px rgba(0,0,0,0.2);
        font-family: 'Jomhuria', cursive;
        line-height: 1;
        letter-spacing: 1px;
        text-decoration: none; 
        display: inline-block; 
    }
    #overlay-blur {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      backdrop-filter: blur(8px);
      background-color: rgba(0, 0, 0, 0.2);
      z-index: 10;
      display: none;
    }

    #frame-container {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 11;
      display: none;
    }

    iframe {
      width: 80vw;
      max-height: 80vh;
      height: 80vh;
      border: none;
      border-radius: 15px;
    }

    #frame-container button {
      position: absolute;
      top: -40px;
      right: 0;
      background: #fff;
      border: 2px solid #999;
      padding: 5px 12px;
      font-size: 18px;
      cursor: pointer;
      border-radius: 10px;
    }

    .task-deadline {
      font-size: 12px;

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
      <a href="isikelasguru.html" class="tambah-kelas" style="text-decoration: none; color: black; font-weight: bold;" onclick="tampilkanTombol()">+</a>
      <div class="profile-icon"></div>
    </div>
  </div>
  
  <div class="container">
    <div class="sidebar" id="sidebar">
      <a href="pet_siswa.html"><div class="pet-image"></div></a> 
      <button> <a href="Beranda_siswa.php" class="a-navbar">Beranda</a></button>
      <button> <a href="forumsiswa.php" class="a-navbar">Tingkatan Tugas</a></button>
      <button> <a href="profil_siswa.html" class="a-navbar">Profil</a></button>
      <button> <a href="FAQ.html" class="a-navbar">FAQ</a></button>
</div>

    <div class="main-content">
      <div class="overlay">
        <div class="task-container">
         <div class="tabs">
          <a href="forum.php" class="active">Forum</a>
          <a href="kumpulantugasSiswa.php">Kumpulan Tugas</a>
        </div>

      <div class="header-image-section">
        <img src="../../gambar/books.png" alt="Header" class="header-bg">
        <h2 class="header-title"><?= $id_tugas[0]['nama_kelas']; ?></h2>
        <?php if (!empty($id_tugas)) : ?>
      <div class="header-text">
      </div>
    <?php endif; ?>
        </div>
          <div class="task-wrapper">
            <div class="task-highlight-box">
              <div class="task-content">
                <p class="task-title"><b>Kerjakan Tugasmu Yuk!</b></p>
                <img src="../../gambar/study.png" alt="Capybara" />
                </a>
              </div>
            </div>

 <div class="task-list">
<?php foreach ($tugas_list as $t) : ?>
  <div class="task-item">
    <img src="../../gambar/fotoguru.png" alt="Guru" class="avatar">
    <div class="task-body">
      <div class="task-header">
        <div class="task-title-container">
          <span class="task-title"><?= htmlspecialchars($t['nama_tugas']); ?></span>
          <span class="task-deadline">
            <br>
            <?php
              $dt = new DateTime($t['deadline']);
              echo 'Deadline: ' . $dt->format('d M Y, H:i');
            ?>
          </span>
        </div>
        <span class="task-status"><?= htmlspecialchars($t['level']); ?></span>
      </div>

      <div class="task-card">
        <img src="../../gambar/pdf.png" alt="PDF" class="pdf-icon">
        <div class="task-desc">
          <p class="task-subtitle"><?= $t['deskripsi']; ?></p>
          <a class="pdf-button" href="../../uploads/<?= $t['file_tugas']; ?>" target="_blank">
            <img src="../../gambar/pdf.png" alt="PDF Icon" />
            <b>Lihat Tugas</b>
          </a>
        </div>
      </div>
      <!-- STATUS PENGUMPULAN -->
     <!-- Cek apakah tugas sudah dikumpulkan -->
  <?php if (in_array($t['id_tugas'], $submitted_tasks)) : ?>
    <a href="SubmitTugas.php?id_tugas=<?= $t['id_tugas'] ?>" class="send-button" title="Edit Tugas">Edit</a>
    <span class="status-label done">Sudah Dikerjakan</span>
  <?php else : ?>
    <a href="forumsiswa.php?id_tugas=<?= $t['id_tugas'] ?>" class="send-button" title="Kerjakan Tugas">Kerjakan Tugas</a>
    <span class="status-label not-done">Belum Dikerjakan</span>
  <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
</div>
<div id="overlay-blur" onclick="closeForm()" style="display:none;"></div>
<div id="frame-container" style="display:none;">
  <button onclick="closeForm()">✕</button>
  <iframe></iframe>
</div>

<script>
  // Sidebar toggle
document.getElementById('menuToggle').addEventListener('click', function () {
  document.getElementById('sidebar').classList.toggle('hidden');
});

// Buka form tambah tugas (jika masih dipakai)
const tambahBtn = document.getElementById('tambah-tugas-btn');
if (tambahBtn) {
  tambahBtn.addEventListener('click', function (event) {
    event.preventDefault();
    const url = this.getAttribute('href');
    openForm(url);
  });
}

function openForm(url) {
  const overlay = document.getElementById('overlay-blur');
  const frameContainer = document.getElementById('frame-container');
  const iframe = frameContainer.querySelector('iframe');

  iframe.src = url;
  overlay.style.display = 'block';
  frameContainer.style.display = 'block';
}

function closeForm() {
  document.getElementById('overlay-blur').style.display = 'none';
  const frameContainer = document.getElementById('frame-container');
  frameContainer.style.display = 'none';
  frameContainer.querySelector('iframe').src = '';
}

</script>

</body>
</html>