<?php
include "../../service/koneksi.php";

// Ambil ID dari GET atau POST
$id = $_GET['id'] ?? $_POST['id'] ?? '';


if (!$id) {
    die("ID tidak ditemukan.");
}

$editMode = false; // Mode edit default false
$sudahDinilai = false;
$catatan = '';
$nilai = '';
$file_jawaban = '';
$waktu_kumpul = '';

// Ambil data pengumpulan
$stmt = $koneksi->prepare("SELECT catatan, nilai, file_jawaban, waktu_kumpul FROM pengumpulan WHERE id_pengumpulan = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Data tidak ditemukan.");
}

$catatan = $data['catatan'];
$nilai = $data['nilai'];
$file_jawaban = $data['file_jawaban'];
$waktu_kumpul = $data['waktu_kumpul'];

$sudahDinilai = !empty($nilai);

// Jika tombol "Edit" ditekan, aktifkan mode edit
if (isset($_POST['editnilai'])) {
    $editMode = true;
}

// Jika tombol "Simpan Nilai" ditekan
if (isset($_POST['kasihnilai'])) {
    $nilaiInput = $_POST['nilai'] ?? '';
    if ($nilaiInput !== '') {
        // Update nilai di database
        $stmtUpdate = $koneksi->prepare("UPDATE pengumpulan SET nilai = ? WHERE id_pengumpulan = ?");
        $stmtUpdate->bind_param("di", $nilaiInput, $id);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        // Setelah update selesai, kembali ke mode view (bukan edit)
        $nilai = $nilaiInput;
        $sudahDinilai = true;
        $editMode = false;

        // Reload halaman supaya data terbaru muncul (optional)
        header("Location: kasihnilai.php?id=" . urlencode($id));
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TaskWell Modal</title>
  <style>

    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      transition: opacity 0.5s ease;
    }
    html, body {
      overflow: hidden;
    }
    .modal-wrapper {
      width: 100vw;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(5px);
      
    }

    .modal-box {
      background-color: #fde8d9;
      width: 650px;
      padding: 0;
      border-radius: 25px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }
    

    .modal-header {
      background-color: #8a4723;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
      padding: 35px;
      color: white;
      position: relative;
      overflow: visible; /* biar gambar bisa keluar dari box */
    }

    .modal-header > div {
      padding-right: 10px; /* beri ruang agar tidak tertabrak capybara */
    }


    .modal-header img {
      position: absolute;
      right: -50px; /* geser ke kanan luar */
      top: -10px;   /* geser sedikit naik */
      height: 130px;
      filter: drop-shadow(0 6px 8px rgba(0, 0, 0, 0.2));
      z-index: 2;
    }

    .modal-body {
      padding: 24px;
    }

    .message-box {
      width: 95%;
      height: 30px;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      resize: none;
      font-family: monospace;
    }

    .file-box {
      display: flex;
      align-items: center;
      background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 8px 12px;
      margin-top: 16px;
      gap: 10px;
      width: 95%;
    }

    .file-box img {
      height: 20px;
    }

    .deadline {
      margin-top: 10px;
      color: red;
      font-size: 14px;
    }

    .edited {
      font-size: 13px;
      color: #333;
    }

    .row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
    margin-top: 24px;
   }

    .level-box, .score-box {
      flex: 1;
      display: flex;
      flex-direction: column;
      background: #f0e0d0;
      padding: 12px;
      border-radius: 16px;
      height: 100%;
      text-align: center;
  }

  .level-button {
    padding: 10px 20px;
    border-radius: 8px;
    background-color: #00b7ff;
    color: white;
    border: none;
    font-weight: bold;
    margin-top: 8px;
    text-align: center; 
  }

  .score-input {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-top: 8px;
    width: 90%;
    text-align: center;
  }

  .submit-btn {
    align-self: flex-end;
    height: 100%;
    padding: 12px 20px;
    background-color: #7b3f1d;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
  }

</style>
</head>
<body style="cursor: url('./../Homes/Asset/cursor.png') 0 0, auto;">
  <div class="modal-wrapper">
    <div class="modal-box">
      <div class="modal-header">
        <div>
          <b>Siswa sudah mengerjakan tugasnya nih! yuk kita cek dan 
          <br>kita nilai, sejauh mana siswa sudah memahami yang kamu ajarkan!</br></b>
        </div>
        <img src="../../gambar/CapyBara2.png" alt="Capybara">
      </div>
      <div class="modal-body">
      <textarea class="message-box" readonly><?= htmlspecialchars($catatan) ?></textarea>
       <div class="file-box">
          <img src="../../gambar/pdf.png" alt="File Icon"> <!-- opsional -->
          <a href="../../uploads/jawaban/<?= htmlspecialchars($file_jawaban) ?>" target="_blank">
            <?= htmlspecialchars($file_jawaban) ?>
          </a>
        </div>

        <div class="deadline">
          Deadline: 12 Oct 2025, 23.59
        </div>
        <div class="edited">
          Edited: 11 Oct 2025, 23.50
        </div>

       <form method="POST" action="">
    <div class="score-box">
        <label><b>Nilai</b></label>
        <input
            type="number"
            step="0.01"
            name="nilai"
            class="score-input"
            min="0"
            max="100"
            value="<?= htmlspecialchars($nilai) ?>"
            <?= ($sudahDinilai && !$editMode) ? 'readonly' : '' ?>
            required
        >
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

        <?php if (!$sudahDinilai || $editMode): ?>
            <button type="submit" name="kasihnilai" class="submit-btn">Simpan Nilai</button>
        <?php else: ?>
            <p class="info-text">✔ Tugas ini sudah dinilai.</p>
            <button type="submit" name="editnilai" class="submit-btn">Edit Nilai</button>
        <?php endif; ?>
    </div>
</form>


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

function closeForm() {
  document.getElementById('overlay-blur').style.display = 'none';
  document.getElementById('frame-container').style.display = 'none';
  document.querySelector('#frame-container iframe').src = '';
}
    // Tutup popup kalau klik di luar iframe (klik area blur)
    overlay.addEventListener('click', function (e) {
      if (!popupBox.contains(e.target)) {
        overlay.classList.add('hidden');
      }
    });

    // Optional: fungsi input nilai dan submit (kalau ada input di iframe)
    const scoreInput = document.querySelector('.score-input');
    const submitBtn = document.querySelector('.submit-btn');

    if (scoreInput && submitBtn) {
      submitBtn.disabled = true;
      submitBtn.style.opacity = '0.5';

      scoreInput.addEventListener('input', () => {
        if (scoreInput.value.trim() !== '') {
          submitBtn.disabled = false;
          submitBtn.style.opacity = '1';
        } else {
          submitBtn.disabled = true;
          submitBtn.style.opacity = '0.5';
        }
      });
    }

</script>

  
</body>
</html>
