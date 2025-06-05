<?php
session_start();
include '../../service/koneksi.php';

// Default sorting
$sort_by = "created_at DESC";

// Cek jika ada parameter sort di URL
if (isset($_GET['sort'])) {
    if ($_GET['sort'] === 'deadline') {
        $sort_by = "deadline ASC";
    } elseif ($_GET['sort'] === 'level') {
        $sort_by = "level ASC";
    }
}

// Ambil data tugas dari database, termasuk nilai dari pengumpulan (jika ada yang sudah dinilai)
$query = "
    SELECT t.*, MAX(p.nilai) AS nilai
    FROM tugas t
    LEFT JOIN pengumpulan p ON t.id_tugas = p.id_tugas
    GROUP BY t.id_tugas
    ORDER BY $sort_by
";

$result = mysqli_query($koneksi, $query);

$id_tugas = [];
while ($row = mysqli_fetch_assoc($result)) {
    $id_tugas[] = $row;
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
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
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
      margin-top: -13px; /* Atau bisa juga pakai margin-top: 2px; sesuai kebutuhan */
      line-height: 1; /* Tambahan untuk memperkecil jarak vertikal */
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
      transition: all 0.3s ease;
    }

    .container.sidebar-hidden {
      grid-template-columns: 0 1fr;
    }

    /* Ikon menu ☰ */
    #menuToggle {
      font-size: 35px;
      cursor: pointer;
      margin-right: 10px;
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

    /* Animasi sidebar */
    .sidebar {
      transition: transform 0.3s ease;
      transform: translateX(0); /* Tampil */
    }

    .sidebar.hidden {
      transform: translateX(-100%); /* Geser keluar layar */
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
      position: flex;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .class-selection {
      text-align: center;
      width: 90%;
      max-width: 700px;
      animation: fadeInUp 0.5s ease;
    }

    .class-card {
      border-radius: 15px;
      padding: 15px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .class-card:hover {
      background-color: #f0e2d0;
      transform: scale(1.02);
    }

    .class-card img {
      width: 50px;
      height: 50px;
      border-radius: 10px;
      margin-right: 15px;
      object-fit: cover;
    }

    .class-info {
      text-align: left;
    }

    .class-selection h2 {
      background-color: #8c6e4c;
      color: white;
      padding: 12px 0;
      border-radius: 12px;
      font-size: 32px;
      margin-bottom: 25px;
      font-family: 'Jomhuria', cursive;
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

    .custom-dropdown {
      position: relative;
      width: 100%;
      font-family: 'Poppins', sans-serif;
    }

    .selected-option {
      background-color: #b5826b; /* warna krem-coklat */
      color: rgb(0, 0, 0);
      padding: 12px 16px;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      text-transform: uppercase;
    }

    .dropdown-options {
      list-style: none;
      margin: 0;
      padding: 0;
      background-color: #f5eee4;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      display: none;
      z-index: 10;
    }

    .dropdown-options li {
      padding: 12px 16px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
    }

    .dropdown-options li:hover {
      background-color: #dfc1ae;
    }

    .section-title {
      font-size: 18px;
      font-weight: 700;
      margin-top: 25px;
      border-bottom: 1px solid #000;
      padding-bottom: 5px;
    }

    .task-card {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: transparent;
      padding: 12px 0;
    }

    .task-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .task-icon {
        width: 30px;
        height: 30px;
        background-color: transparent;
        border-radius: 4px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .task-icon img {
        width: 40px;
        height: auto;
        object-fit: contain;
    }

    .tag {
        display: inline-block;
        min-width: 70px;
        text-align: center;
        font-size: 13px;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        color: rgb(0, 0, 0);
        line-height: 1.2;
        margin-top: 4px;
    }

    .hard {
        background-color: #de4141;
    }

    .medium {
        background-color: #6ebde8;
    }

    .easy {
        background-color: #41de6b;
    }

    .deadline {
      font-size: 12px;
      color: #555;
    }

    /* Tambahan baru */
    .sidebar-courses {
     margin-top: 5px;
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;
      padding-bottom: 180px;
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

    .task-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  text-decoration: none;
  padding: 12px;
  margin-bottom: 12px;
  background-color: #fff;
  border-radius: 8px;
  transition: background-color 0.3s;
  color: inherit;
}

.task-item:hover {
  background-color: #f0f0f0;
}

.task-title {
  font-weight: bold;
}

.task-difficulty {
  background-color: #856451;
  color: white;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  margin-top: 4px;
  width: 70px;
  text-align: center;
}

.task-left {
  display: flex;
  gap: 12px;
  align-items: center;
}

.task-deadline {
  font-size: 0.9rem;
  color: #444;
}
.task-status {
  padding: 4px 8px;
  background-color: #856451;
  border-radius: 6px;
  font-weight: bold;
  color: white;
  display: inline-block;
}

.status-easy {
  background-color: #4CAF50; /* Hijau */
}

.status-medium {
  background-color: #2196F3; /* Biru */
}

.status-hard {
  background-color: #f44336; /* Merah */
}

.sub-btn {
      text-decoration: none;
      color: black;
    }

    .a-navbar {
      text-decoration: none;
          color: black;
    }

    .sidebar-divider {
      width: 100%;
      border: none;
      height: 1px;
      background-color: #c9b9a5;
      margin: 10px 0;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
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
      <a href="isikelasguru.html" style="text-decoration: none; color: black; font-weight: bold;" onclick="tampilkanTombol()">+</a>
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
            <a href="forumsiswa.php">Forum</a>
            <a href="kumpulantugasSiswa.php" class="active">Kumpulan Tugas</a>
          </div>

          <div class="custom-dropdown">
            <div class="selected-option" onclick="toggleDropdown()">Tingkatan Tugas</div>
            <ul class="dropdown-options" id="dropdownMenu">
              <li onclick="selectOption('Tingkatan Tugas', 'level')">Tingkatan Tugas</li>
              <li onclick="selectOption('Deadline', 'deadline')">Deadline</li>
            </ul>
          </div>

        <?php foreach ($id_tugas as $t) : ?>
  <?php
    // Tentukan kelas berdasarkan level (opsional)
    $level = strtolower($t['level']);
    $levelClass = '';
    if ($level === 'mudah') {
        $levelClass = 'easy';
    } elseif ($level === 'sedang') {
        $levelClass = 'medium';
    } elseif ($level === 'sulit') {
        $levelClass = 'hard';
    }

    // Cek nilai
    $nilaiText = is_null($t['nilai']) ? 'Belum dinilai' : 'Nilai: ' . $t['nilai'];
  ?>

  <div class="task-section">
    <div class="section-title"><?= htmlspecialchars($t['nama_tugas']); ?></div>
    <div class="task-card" onclick="window.location.href='SubmitTugas.php?id_tugas=<?= $t['id_tugas']; ?>'" style="cursor: pointer;">
      <div class="task-left">
        <img src="../../gambar/pdf.png" alt="PDF" width="40">
        <div>
          <div class="task-title"><?= htmlspecialchars($t['nama_tugas']); ?></div>
          <div class="task-difficulty <?= $levelClass ?>"><?= htmlspecialchars($t['level']); ?></div>
          <div class="task-nilai"><?= $nilaiText ?></div> <!-- Tambahan: Tampilkan nilai -->
        </div>
      </div>
      <div class="task-deadline">
        Tenggat: <?= htmlspecialchars($t['deadline']); ?>
      </div>
    </div>
  </div>
<?php endforeach; ?>

        </div>
      </div>
    </div>
  </div>

<script>
  function toggleDropdown() {
    const menu = document.getElementById("dropdownMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
  }

  function selectOption(label, value) {
    document.querySelector('.selected-option').textContent = label;
    document.getElementById("dropdownMenu").style.display = "none";
    const url = new URL(window.location.href);
    url.searchParams.set("sort", value);
    window.location.href = url.toString();
  }

  document.addEventListener("click", function(e) {
    const dropdown = document.querySelector(".custom-dropdown");
    if (!dropdown.contains(e.target)) {
      document.getElementById("dropdownMenu").style.display = "none";
    }
  });
</script>
</body>
</html>


