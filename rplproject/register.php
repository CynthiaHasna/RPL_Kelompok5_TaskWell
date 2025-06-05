<?php
    include "service/koneksi.php";

    if(isset($_POST["register"])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        if ($role != 'guru' && $role != 'siswa') {
         die('Role tidak valid.');
        }

        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";

      if($koneksi->query($sql)) {
            header("Location:layout/masuk.html");
            exit(); // Penting agar skrip tidak lanjut jalan
        } else {
            echo "Data gagal masuk: " . $koneksi->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Task Well - Blur Background</title>
  <link href="https://fonts.googleapis.com/css2?family=Jomhuria&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Jomhuria', cursive;
    }

    body, html {
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      overflow: hidden;
    }

    .background-layer {
      position: fixed;
      inset: 0;
      background: linear-gradient(to bottom, #f7ecd9, #e6d4b4);
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      z-index: 0;
    }

    .content-layer {
      position: relative;
      z-index: 1;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .topbar {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      border-bottom: 1px solid #d4c0a5;
      background-color: #f7ecd9;
      filter: blur(2px);
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
      background-image: url('gambar/Capybara.png');
      background-size: cover;
    }

    .app-title {
      font-size: 45px;
      font-weight: bold;
      line-height: 1;
    }

    .subtitle {
      font-size: 28px;
      margin-top: -10px;
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
      background-color: #b79c7a;
      border-radius: 50%;
      background-image: url('gambar/Capybara.png');
      background-size: cover;
      border: 1px solid #000;
    }

    .container {
      display: flex;
      flex: 1;
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
      filter: blur(2px);
    }

    .sidebar button {
      background-color: #e3d0b7;
      border: none;
      padding: 8px 10px;
      border-radius: 20px;
      font-size: 25px;
      cursor: pointer;
      width: 100%;
    }

    .sidebar button:hover {
      background-color: #d4b999;
    }

    .main-content {
      flex-grow: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .login-card {
      background-color: #8b6651;
      padding: 20px;
      border-radius: 30px;
      text-align: center;
      color: white;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 600px;
      font-size: 28px;
      letter-spacing: 1px;
    }

    .login-card img {
      width: 180px;
      margin-bottom: -30px;
      margin-top: -75px;
    }

    .auth-buttons {
  margin-top: 20px;
  display: flex;
  gap: 10px;
  justify-content: center;
  font-family: 'Poppins', sans-serif;
}

.auth-buttons button {
  background-color: rgb(208, 169, 146);
  color: black;
  border: none;
  padding: 12px 32px;
  border-radius: 12px;
  font-weight: bold;
  cursor: pointer;
  box-shadow: 2px 4px 0 rgba(0, 0, 0, 0.2);
  transition: 0.3s;
  font-family: 'Poppins', sans-serif;
}

.auth-buttons button:hover {
  background-color: #6e4732;
  color: white;
}

.wiggle {
  animation: tuingTuing 1.2s ease-out infinite;
  transform-origin: center;
}

@keyframes tuingTuing {
  0% {
    transform: scale(1);
  }
  20% {
    transform: scale(1.2, 0.8);
  }
  40% {
    transform: scale(0.9, 1.1);
  }
  60% {
    transform: scale(1.05, 0.95);
  }
  80% {
    transform: scale(0.98, 1.02);
  }
  100% {
    transform: scale(1);
  }
}

form {
  max-width: 360px;
  margin: 20px auto;
  font-family: Poppins, sans-serif;
}

form label {
  display: block;
  margin-bottom: 4px;
  font-size: 20px;
  color: #fcecd5;
}

form input[type="text"],
form input[type="email"],
form input[type="password"],
form select {
  width: 100%;
  height: 38px;
  padding: 6px 10px;
  margin-bottom: 16px; /* jarak input ke label berikutnya */
  border: 1px solid #888;
  border-radius: 0;
  font-size: 20px;
  box-sizing: border-box;
}

form input[type="text"]:focus,
form input[type="email"]:focus,
form input[type="password"]:focus,
form select:focus {
  border-color: #333;
  outline: none;
}

form button {
  width: 100%;
  height: 40px;
  background-color: #fcecd5;
  color:rgb(0, 0, 0);
  border: none;
  border-radius: 0;
  cursor: pointer;
  font-size: 20px;
}

form button:hover {
  background-color: #d0a076;
}
    
  </style>
</head>
<body style="cursor: url('./../Homes/Asset/cursor.png') 0 0, auto;">

<div class="background-layer"></div>

  <div class="content-layer">
    <!-- TOPBAR -->
    <div class="topbar">
      <div class="topbar-left">
        <p class="menu-icon">☰</p>
        <div class="logo"></div>
        <div>
          <div class="app-title">Task Well</div>

        </div>
      </div>
      <div class="topbar-icons">
        <p>+</p>
        <div class="profile-icon"></div>
      </div>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="container">
      <!-- SIDEBAR -->
      <div class="sidebar">
        <button>Beranda</button>
        <button>Profile</button>
        <button>FAQ</button>
      </div>

      <!-- MAIN LOGIN CONTENT -->
      <div class="main-content">
        <div class="login-card">
          <img src="gambar/study.png" alt="Capybara Wisuda" class="wiggle"/>
          <p style="margin-top: 10px;">
            sebelum kita mulai, kita harus daftar dulu nih akan menjadi guru atau siswa ya?, yuk isi form nya! pastikan hanya satu akun pada satu role!
          </p>
          <p style="margin-top: 10px; padding: 8px 15px; border: 2px solidrgb(118, 90, 65); background-color: #d0a076; border-radius: 4px; display: inline-block; color: black;">
  REGISTER
</p>

          <div>
    <form id="loginForm" method="POST" action="">
        
  <label for="username">Masukkan nama Lengkap:</label>
  <input type="text" id="username" name="username" placeholder="Nama Lengkap" required>
  <label for="email">Masukkan Email:</label>
  <input type="email" id="email" name="email" placeholder="email@example.com" required>
  
  <label for="password">Masukkan Password:</label>
  <input type="password" id="password" name="password" placeholder="Password" required>
  
  <label for="role">Pilih Role:</label>
  <select id="role" name="role" required>
    <option value="" disabled selected>Pilih Role</option>
    <option value="guru">Guru</option>
    <option value="siswa">Siswa</option>
  </select>
  
<button type="submit" name="register">Submit</button>


</form>

  </div>

        </div>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
  });
</script>

</body>
</html>