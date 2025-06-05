<?php
session_start();
include '../../service/koneksi.php';

$pesan = "";

// Ambil id_tugas dari POST atau SESSION
$id_tugas = 0;
if (isset($_GET['id_tugas'])) {
    $id_tugas = (int)$_GET['id_tugas'];
    $_SESSION['id_tugas'] = $id_tugas;
}elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_tugas'])) {
    $id_tugas = (int)$_POST['id_tugas'];
    $_SESSION['id_tugas'] = $id_tugas; // simpan untuk digunakan kembali
} elseif (isset($_SESSION['id_tugas'])) {
    $id_tugas = (int)$_SESSION['id_tugas'];
}

// Cek apakah tugas ada di database
$deadline = "";
if ($id_tugas > 0) {
    $stmt = $koneksi->prepare("SELECT deadline FROM tugas WHERE id_tugas = ?");
    $stmt->bind_param("i", $id_tugas);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $pesan = "Tugas tidak ditemukan di database.";
    } else {
        $stmt->bind_result($deadline);
        $stmt->fetch();
    }
    $stmt->close();
} else {
    $pesan = "ID tugas tidak valid.";
}

// Proses jika tidak ada error awal
if (empty($pesan) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file_jawaban']) && isset($_SESSION['id_user'])) {
        $id_user = (int)$_SESSION['id_user'];
        $catatan = trim($_POST['catatan'] ?? '');
        $waktu_kumpul = date("Y-m-d H:i:s");

        $file = $_FILES['file_jawaban']['name'];
        $tmp = $_FILES['file_jawaban']['tmp_name'];
        $folder = "../../uploads/jawaban/";

        // Validasi ekstensi
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $allowed_ext = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'rar', 'txt'];

        if (!in_array($ext, $allowed_ext)) {
            $pesan = "Jenis file tidak diizinkan.";
        } else {
            // Cek apakah sudah pernah mengumpulkan
            $cek = $koneksi->prepare("SELECT id_pengumpulan FROM pengumpulan WHERE id_user = ? AND id_tugas = ?");
            $cek->bind_param("ii", $id_user, $id_tugas);
            $cek->execute();
            $cek->store_result();

            if ($cek->num_rows > 0) {
                $pesan = "Anda sudah mengumpulkan tugas ini.";
            } else {
                // Upload file
                $file_baru = uniqid("jawaban_") . "." . $ext;
                $target = $folder . $file_baru;

                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true); // buat folder jika belum ada
                }

                if (move_uploaded_file($tmp, $target)) {
                    // Simpan ke database
                    $stmt = $koneksi->prepare("INSERT INTO pengumpulan (id_tugas, id_user, file_jawaban, catatan, nilai, waktu_kumpul) VALUES (?, ?, ?, ?, 0, ?)");
                    $stmt->bind_param("iisss", $id_tugas, $id_user, $file_baru, $catatan, $waktu_kumpul);

                    if ($stmt->execute()) {
                        unset($_SESSION['id_tugas']); // bersihkan session
                        header("Location: forumsiswa.php?status=sukses");
                        exit();
                    } else {
                        $pesan = "Gagal menyimpan ke database: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $pesan = "Gagal mengupload file.";
                }
            }
            $cek->close();
        }
    } else {
        $pesan = "Data tidak lengkap atau Anda belum login.";
    }
}

// Tampilkan pesan jika ada
if (!empty($pesan)) {
    echo "<script>alert(" . json_encode($pesan) . "); window.history.back();</script>";
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SubmitTugas</title>
  <link href="https://fonts.googleapis.com/css2?family=Jomhuria&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-image: url('../../gambar/Background.png');
      height: 100vh;
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
      z-index: 20;         
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
      font-size: 45px;
      font-weight: bold;
      line-height: 1;
    }

    .subtitle {
      font-size: 28px;
      margin-top: -5px;
      color: #333;
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
    }

    .profile-icon {
      width: 40px;
      height: 40px;
      background-color: #856451;
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

      .a-navbar {
    color: inherit; /* Mengikuti warna tombol (biasanya putih di sidebar) */
    text-decoration: none; /* Menghilangkan underline */
  }

  button {
    background-color: transparent;
    border: none;
    padding: 10px 0;
    text-align: left;
    width: 100%;
    color: #000000;
    cursor: pointer;
    justify-content: center;  
    display: flex;

}

  button:hover {
    background-color: #495057;
  }

    .main-content {
      flex-grow: 1;
      padding: 20px;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(1px);
      -webkit-backdrop-filter: blur(1px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 999;
    }

    .submission-box {
      background-color: #e7d5bc;
      padding: 0;
      border-radius: 15px;
      width: 750px;
      box-shadow: 2px 4px 6px #444;
    }

    .submission-header {
      background-color: #8b5d41;
      color: white;
      padding: 20px;
      font-weight: bold;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-family: Poppins;
    }

    .submission-header img {
      width: 190px;
    }

    .submission-body {
      padding: 20px;
      display: flex;
      gap: 20px;
    }

    .submission-form {
      flex: 2;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .submission-form textarea,
    .submission-form input[type="file"] {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #333;
      font-family: 'Poppins', sans-serif;
      font-style: italic;
      background-color: white;
    }

    .level-info {
      flex: 1;
      background-color: white;
      border-radius: 10px;
      padding: 20px 10px 20px 10px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      border: 1px solid #333;
      height: 50%;
    }

    .level-info h4 {
      margin-bottom: 10px;
      font-size: 28px;
      background-color: #d4c0a5;
      padding: 10px;
      border-radius: 15px;
      text-align: center;
      font-family: 'Jomhuria', cursive;
      letter-spacing: 1px;
    }

    .level-button {
      padding: 10px 20px;
      background-color: #63b7e3;
      color: black;
      border: none;
      border-radius: 15px;
      font-style: italic;
      font-size: 14px;
      cursor: default;
      width: 100%;
    }

    .submission-footer {
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .deadline-info {
      font-size: 12px;
      width: 46%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #333;
      font-family: 'Poppins', sans-serif;
      background-color: white;
    }

    .deadline-info span {
      color: red;
      font-weight: bold;
    }

    .status-info {
      font-style: italic;
      color: gray;
    }

    .submit-btn {
      background-color: #8b5d41;
      color: white;
      padding: 10px 30px;
      border-radius: 15px;
      font-size: 28px;
      border: none;
      cursor: pointer;
      font-family: 'Jomhuria', cursive;
      letter-spacing: 1px;
    }

    /* Tambahan baru */
    .sidebar-courses {
      margin-top: auto;
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;
      padding-bottom: 20px;
    }

    .course-button {
      background-color: #d4c0a5;
      color: #000;
      font-size: 10px;
      text-align: left;
      padding: 10px 20px;
      width: 100%;
      height: 80%;
      border-radius: 40px;
      box-shadow: 2px 4px 6px rgba(0,0,0,0.2);
      font-family: 'Poppins', sans-serif;
      line-height: 1.3;
    }

    .sidebar-divider {
      width: 100%;
      border: none;
      height: 1px;
      background-color: #c9b9a5;
      margin: 10px 0;
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


  </style>
</head>
<body style="cursor: url('../../Homes/Asset/cursor.png') 0 0, auto;">
  <div class="topbar">
    <div class="topbar-left">
      <div class="logo"></div>
      <div>
        <div class="app-title">Task Well</div>
        <div class="subtitle">Siswa</div>
      </div>
    </div>
    <div class="topbar-icons">
      <p>+</p>
      <p>☰</p>
      <div class="profile-icon"></div>
    </div>
  </div>

  <div class="container">
    <div class="sidebar">
      <button> <a href="Beranda_siswa.php" class="a-navbar">Beranda</a></button>
      <button> <a href="TingkatanTugas.php" class="a-navbar">Tingkatan Tugas</a></button>
      <button> <a href="profil_siswa.php" class="a-navbar">Profil</a></button>
      <button> <a href="FAQ.php" class="a-navbar">FAQ</a></button>


      <!-- Garis pemisah -->
        <hr class="sidebar-divider">

        <!-- Tambahan: Course List di kiri bawah -->
       
    </div>

    <div class="main-content">
      <div class="overlay">
        <div class="submission-box">
          <div class="submission-header">
            <div>Yuk kumpulkan tugasmu! Jangan lupa di cek lagi ya pengiriman file dan deskripsi jawabannya, jangan lupa beristirahat!</div>
            <img src="../../gambar/capybarasiswa.png" alt="capy">
          </div>
          <div class="submission-body">
          <div class="submission-form">
          <form action="SubmitTugas.php" method="post" enctype="multipart/form-data">
               <input type="hidden" name="id_tugas" value="<?= $id_tugas ?>">
          <textarea name="catatan" rows="3" placeholder="Masukkan deskripsi tugas..."></textarea>
            
            <input type="file" name="file_jawaban" required placeholder="Masukkan file bahan ajar...">
            
            <div class="deadline-info">
<p><span>Deadline: <?= htmlspecialchars($deadline) ?></span></p>
                  <p class="status-info">Edited: Belum mengumpulkan</p>
              </div>
            
            <div class="submission-footer" style="padding-left: 0;">
              <button type="submit" name="SubmitTugas" class="submit-btn">Kirim</button>
            </div>
          </form>
        </div>

        </div>
      </div>
    </div>
  </div>
   <script>
  document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.querySelector(".overlay");
    const submissionBox = document.querySelector(".submission-box");

    overlay.addEventListener("click", function (event) {
      if (!submissionBox.contains(event.target)) {
        window.location.href = "kumpulantugasSiswa.php";
      }
    });
  });
  </script>

</body>
</html>