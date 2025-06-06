<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Task Well - FAQ</title>
  <link href="https://fonts.googleapis.com/css2?family=Jomhuria&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
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
      margin-top: -13px; /* Atau bisa juga pakai margin-top: 2px; sesuai kebutuhan */
      line-height: 1; /* Tambahan untuk memperkecil jarak vertikal */
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
      transition: all 0.3s ease;
    }

    .container.sidebar-hidden {
      grid-template-columns: 0 1fr;
    }


    .pet-image {
      width: 120px;
      height: 120px;
      background-image: url('../../gambar/CapyLove.png'); /* Ganti dengan nama file pet kamu */
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
      padding: 2px 2px 2px 2px;
    }

    /* Ikon menu ☰ */
    #menuToggle {
      font-size: 35px;
      cursor: pointer;
      margin-right: 10px;
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
      padding: 40px 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    .faq {
      width: 100%;
      max-width: 800px;
    }

    .faq-item {
      background-color: #dfcfba;
      border-radius: 15px;
      margin-bottom: 15px;
      box-shadow: 1px 2px 4px rgba(0,0,0,0.2);
      border: 1px solid #333;
      font-family: 'Jomhuria', cursive;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .faq-question {
      font-weight: 700;
      padding: 15px 20px;
      font-size: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      letter-spacing: 1.5px;
      border-bottom: 1px solid #333;
    }

    .faq-answer {
      background-color: #f7f2ea;
      padding: 0 20px;
      font-size: 16px;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.4s ease, padding 0.4s ease;
    }

    .faq-item.active .faq-answer {
      padding: 15px 20px;
      max-height: 300px;
    }

    .arrow {
      transition: transform 0.3s ease;
    }

    .faq-item.active .arrow {
      transform: rotate(180deg);
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

  .pet-image:hover {
  transform: scale(1.1) rotate(5deg); /* zoom 110% dan rotate 5 derajat */
  cursor: pointer; /* agar kursor berubah saat hover */
}

    .sidebar-divider {
      width: 100%;
      border: none;
      height: 1px;
      background-color: #c9b9a5;
      margin: 10px 0;
    }
  </style>
</head>
<body style="cursor: url('../../Homes/Asset/cursor.png') 0 0, auto;">
  <div class="topbar">
    <div class="topbar-left">
      <p id="menuToggle">☰</p>
      <div class="logo"></div>
      <div>
        <div class="app-title">Task Well</div>
        <div class="subtitle">Siswa</div>
      </div>
    </div>

    <div class="topbar-icons">
      <a href="PBKelas.php" class="tambah-kelas" style="text-decoration: none; color: black; font-weight: bold;" onclick="tampilkanTombol()">+</a>
      <div class="profile-icon"></div>
    </div>
  </div>

  <div class="container">
    <div class="sidebar" id="sidebar">
      <a href="pet_siswa.php"><div class="pet-image"></div></a> 
      <button> <a href="Beranda_siswa.php" class="a-navbar">Beranda</a></button>
      <button> <a href="TingkatanTugas.php" class="a-navbar">Tingkatan Tugas</a></button>
      <button> <a href="profil_siswa.php" class="a-navbar">Profil</a></button>
      <button> <a href="#" class="a-navbar">FAQ</a></button>
    
    <!-- Garis pemisah -->
    <hr class="sidebar-divider">

    <!-- Tambahan: Course List di kiri bawah -->
        <div class="sidebar-courses">
          <a href="BerandaKurpem.php" class="a-navbar"><div class="course-button">
            <strong>KURIKULUM PEMBELAJARAN</strong><br>
            <small>Guru. Kartika Diana</small>
            </a>
          </div>
           <a href="BerandaAlpro.php" class="a-navbar"><div class="course-button">
            <strong>ALGORITMA PEMROGRAMAN</strong><br>
            <small>Guru. Alya Nurul Hanifah</small>
            </a>
          </div>
        </div>
    </div>

    <div class="main-content">
      <div class="faq">
        <div class="faq-item active">
          <div class="faq-question">
            Bagaimana jika tidak mengumpulkan tugas?
            <span class="arrow">▾</span>
          </div>
          <div class="faq-answer">
            Pada TaskWell ini, terdapat fitur pet untuk menjaga tugas selalu dikerjakan. Apabila tidak dikerjakan, maka pet yang tersedia akan mati. Namun apabila ada tugas lainnya/sebelumnya dikerjakan, pet akan hidup kembali.
          </div>
        </div>

        <div class="faq-item">
          <div class="faq-question">
            Berapa batas tugas yang bisa di berikan?
            <span class="arrow">▾</span>
          </div>
          <div class="faq-answer">
            Batas tugas tergantung pada pengaturan guru. Namun secara umum, tidak lebih dari 5 tugas dalam satu minggu.
          </div>
        </div>

        <div class="faq-item">
          <div class="faq-question">
            Siapa saja yang bisa menggunakan TaskWell?
            <span class="arrow">▾</span>
          </div>
          <div class="faq-answer">
            TaskWell dapat digunakan oleh siswa dan guru dalam ekosistem pembelajaran sekolah.
          </div>
        </div>

        <div class="faq-item">
          <div class="faq-question">
            Apa maksud dari level tugas?
            <span class="arrow">▾</span>
          </div>
          <div class="faq-answer">
            Level tugas menunjukkan tingkat kesulitan dan urgensi dari tugas yang diberikan.
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
      item.querySelector('.faq-question').addEventListener('click', () => {
        item.classList.toggle('active');
      });
    });

    document.getElementById('menuToggle').addEventListener('click', function () {
      const sidebar = document.getElementById('sidebar');
      const container = document.getElementById('mainContainer');
      sidebar.classList.toggle('hidden');
      container.classList.toggle('sidebar-hidden');
    });
  </script>
</body>
</html>