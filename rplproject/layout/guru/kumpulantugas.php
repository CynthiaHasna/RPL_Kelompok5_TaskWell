<?php
session_start();
include "../../service/koneksi.php"; // pastikan ada koneksi DB

$id_tugas = $_GET['id_tugas'];
// Ambil info tugas
$queryTugas = mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_tugas='$id_tugas'");
$dataTugas = mysqli_fetch_assoc($queryTugas);

// Ambil daftar siswa yang sudah mengumpulkan tugas
$queryPengumpulan = mysqli_query($koneksi, "
  SELECT p.*, u.username 
  FROM pengumpulan p
  JOIN users u ON p.id_user = u.id_user
  WHERE p.id_tugas = '$id_tugas'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Task Well - Guru</title>
  <link href="https://fonts.googleapis.com/css2?family=Jomhuria&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Jomhuria', cursive;
    }

    html, body {
      overflow: hidden;
    }

    body {
      font-family: 'Inter', sans-serif;
      display: flex;
      flex-direction: column;
      height: 100vh;
      background-image: url('../../gambar/Background.png');
      overflow: auto;
      transition: opacity 0.5s ease;
    }

    .topbar {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 17%;
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

    .title-block {
      line-height: 1.2;
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
      border-radius: 50%;
      background-image: url('../../gambar/Capybara.png');
      background-size: cover;
      border-radius: 50%;
      background-position: center;
      border: 1px solid #000;
    }

    .container {
      display: grid;
      grid-template-columns: auto 1fr;
      flex-grow: 1;
      height: 100%;
      transition: all 0.3s ease;
      height: calc(100vh - 90px);
    }

    .menu-toggle {
      color: #333;
      font-size: 30px;
      cursor: pointer;
      margin-right: 10px;
      user-select: none;
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
      transition: transform 0.3s ease; /* Ubah dari width ke transform */
      transform: translateX(0); /* Default: tampil */
      overflow-x: hidden;
    }
    .sidebar.open {
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

    .sub-btn {
      background-color: #d4c0a5;
      color: #000;
      font-size: 20px;
      text-align: left;
      padding: 10px 20px;
      width: 100%;
      height: 13%;
      border-radius: 40px;
      box-shadow: 2px 4px 6px rgba(0,0,0,0.2);
      font-family: 'Jomhuria', cursive;
      line-height: 1;
      letter-spacing: 1px; 
    }

        /* Tambahan baru */
        .sidebar-courses {
      margin-top: 5px;
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;
      padding-bottom: 190px;
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

    .sidebar-divider {
      width: 100%;
      border: none;
      height: 1px;
      background-color: #c9b9a5;
      margin: 10px 0;
    }

    .divider {
      width: 100%;
      border: none;
      height: 1px;
      background-color: #c9b9a5;
      margin: 10px 0;
    }

    .main-content {
      flex: 1;
      padding: 25px;
      overflow-y: auto;
    }

    .header-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      letter-spacing: 1px;
    }

    .header-section h2 {
      font-size: 33px;
      font-family: 'Jomhuria', cursive;
      border-bottom: 2px solid black;
      padding-bottom: 5px;
      margin-bottom: 8px;
      width: 72%;
    }

    .task-info hr {
  width: 100%;
  border: none;
  border-top: 1px solid #ccc;
}


    .tag-hard {
      background-color: #d84c4c;
      color: white;
      padding: 2px 10px;
      border-radius: 10px;
      font-size: 18px;
    }

    .deadline {
      font-size: 16px;
      color: #444;
    }

    .task-list {
      margin-top: 10px;
    }

    .task-item {
      background-color: #E8DAC8;
      padding: 12px 20px;
      border-radius: 25px;
      margin-bottom: 10px;
      font-size: 22px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 1px 2px 4px rgba(0,0,0,0.1);
      cursor: pointer;
      font-family: 'Jomhuria', cursive;
      transition: all 0.2s ease;
      text-decoration: none;
      color: inherit;
    }

    .task-item:hover {
      background-color: #d4b999;
      transform: scale(1.01);
      box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .task-item small {
      font-size: 18px;
      color: #555;
      display: block;
      margin-top: 5px;
    }

    .dropdown-icon {
      width: 0;
      height: 0;
      border-left: 7px solid transparent;
      border-right: 7px solid transparent;
      border-top: 10px solid #5c4433;
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
     .sub-btn {
      text-decoration: none;
      color: black;
    }
    .sub-btn:hover {
      background-color: #d4b999;
    }
    .a-navbar {
      text-decoration: none;
          color: black;
    }

    
    @media (max-width: 768px) {
      .menu-toggle-circle {
        display: flex;
      }

      .sidebar {
        position: fixed;
        top: 90px;
        left: -260px;
        height: calc(100vh - 90px);
        z-index: 9;
      }

      .sidebar.open {
        left: 0;
      }
    

      .main-content {
        padding: 20px;
      }
    }
  </style>
</head>
<body style="cursor: url('./../Homes/Asset/cursor.png') 0 0, auto;">

  <div class="topbar">
    <div class="topbar-left">
      <div class="menu-toggle-circle">
        <p class="menu-toggle">☰</p>
      </div>
      <div class="logo"></div>
      <div class="title-block">
        <div class="app-title">Task Well</div>
      <div class="subtitle"><?= $_SESSION['username'] ?></div>

      </div>
    </div>
    <div class="topbar-icons">
      <div class="profile-icon"></div>
    </div>
  </div>

  <div class="container">
    <div class="sidebar" id="sidebar">
      <a href="pet_siswa.html"><div class="pet-image"></div></a> 
      <button> <a href="berandaGuru.php" class="a-navbar">Beranda</a></button>
      <button> <a href="profile.html" class="a-navbar">Profil</a></button>
      <button> <a href="FAQ.html" class="a-navbar">FAQ</a></button>
</div>

    <div class="main-content">
      <div class="header-section">
        <h2>DAFTAR PENGUMPULAN TUGAS</h2>
        <div class="task-info">
          <div style="display: flex; align-items: center; gap: 10px;">
              <hr>
        </div>
        </div>
      </div>


<div class="task-list">
  <?php if (mysqli_num_rows($queryPengumpulan) > 0): ?>
<?php while($row = mysqli_fetch_assoc($queryPengumpulan)): ?>
  <div class="task-item">
    <span onclick="openFile('<?= '../../file_jawaban/' . $row['file_jawaban']; ?>')">
      <?= htmlspecialchars($row['username']); ?>
    </span>

    <?php if (!empty($row['nilai'])): ?>
  <span style="margin-left: 20px; font-size: 18px; color: green;">✔ Telah Dinilai (<?= $row['nilai']; ?>)</span>
  <a href="#" class="btn-edit" data-url="kasihnilai.php?id=<?= $row['id_pengumpulan']; ?>&edit=true" onclick="openIframe(event, this)" style="margin-left: 10px; background-color: #d4c0a5; padding: 5px 10px; border-radius: 10px; text-decoration: none;">Edit</a>
<?php else: ?>
  <a href="#" class="btn-nilai" data-url="kasihnilai.php?id=<?= $row['id_pengumpulan']; ?>" onclick="openIframe(event, this)" style="margin-left: 20px; background-color: #d4c0a5; padding: 5px 10px; border-radius: 10px; text-decoration: none;">Beri Nilai</a>
<?php endif; ?>

  </div>
<?php endwhile; ?>

  <?php else: ?>
    <p style="font-size: 20px;">Belum ada siswa yang mengumpulkan tugas ini.</p>
  <?php endif; ?>
</div>

<div id="overlay-blur" onclick="closeForm()" style="display:none;"></div>
            <div id="frame-container" style="display:none;">
              <button onclick="closeForm()">✕</button>
              <iframe></iframe>
            </div>
            </div>
<script>
  document.getElementById('tambah-tugas-btn').addEventListener('click', function(event) {
  event.preventDefault();  // cegah pindah halaman
  const url = this.getAttribute('href');
  document.getElementById('overlay-blur').style.display = 'block';
  document.getElementById('frame-container').style.display = 'block';
  document.querySelector('#frame-container iframe').src = url;
});

  function openIframe(event, element) {
    event.preventDefault(); // cegah link pindah halaman
    const url = element.getAttribute('data-url');
    document.getElementById('overlay-blur').style.display = 'block';
    document.getElementById('frame-container').style.display = 'block';
    document.querySelector('#frame-container iframe').src = url;
  }

function closeForm() {
  document.getElementById('overlay-blur').style.display = 'none';
  document.getElementById('frame-container').style.display = 'none';
  document.querySelector('#frame-container iframe').src = '';

  // Refresh isi daftar pengumpulan via AJAX
  const idTugas = <?= json_encode($id_tugas); ?>;
  fetch('load_pengumpulan.php?id_tugas=' + idTugas)
    .then(response => response.text())
    .then(html => {
      document.getElementById('taskListContainer').innerHTML = html;
    });
}

</script>

</body>
</html>
