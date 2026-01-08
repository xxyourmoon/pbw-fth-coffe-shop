<?php
require_once 'koneksi.php';
require_once 'upload_foto.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (isset($_POST['simpan'])) {
  $judul = $_POST['judul'];
  $isi = $_POST['isi'];
  $tanggal = date('Y-m-d H:i:s');
  $username = $_SESSION['username'];

  if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $gambar_lama = $_POST['gambar_lama'];

    if (!empty($_FILES['gambar']['name'])) {
      if (!empty($gambar_lama) && file_exists('img/' . $gambar_lama)) {
        unlink('img/' . $gambar_lama);
      }
      $upload = upload_foto($_FILES['gambar']);
      if ($upload['status']) {
        $gambar = $upload['message'];
      } else {
        echo "<script>alert('Gagal upload gambar: " . $upload['message'] . "'); window.location.href = 'admin.php?page=article';</script>";
        exit;
      }
    } else {
      $gambar = $gambar_lama;
    }

    $stmt = $conn->prepare("UPDATE article SET judul=?, isi=?, gambar=?, tanggal=?, username=? WHERE id=?");
    $stmt->bind_param("sssssi", $judul, $isi, $gambar, $tanggal, $username, $id);

    if ($stmt->execute()) {
      echo "<script>alert('Artikel berhasil diupdate!'); window.location.href = 'admin.php?page=article';</script>";
    } else {
      echo "<script>alert('Gagal mengupdate artikel!'); window.location.href = 'admin.php?page=article';</script>";
    }
    $stmt->close();
  } else {
    $gambar = '';
    if (!empty($_FILES['gambar']['name'])) {
      $upload = upload_foto($_FILES['gambar']);
      if ($upload['status']) {
        $gambar = $upload['message'];
      } else {
        echo "<script>alert('Gagal upload gambar: " . $upload['message'] . "'); window.location.href = 'admin.php?page=article';</script>";
        exit;
      }
    }

    $stmt = $conn->prepare("INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $judul, $isi, $gambar, $tanggal, $username);

    if ($stmt->execute()) {
      echo "<script>alert('Artikel berhasil ditambahkan!'); window.location.href = 'admin.php?page=article';</script>";
    } else {
      echo "<script>alert('Gagal menambahkan artikel!'); window.location.href = 'admin.php?page=article';</script>";
    }
    $stmt->close();
  }
  exit;
}

if (isset($_POST['hapus'])) {
  $id = $_POST['id'];
  $gambar = $_POST['gambar'];

  if (!empty($gambar) && file_exists('img/' . $gambar)) {
    unlink('img/' . $gambar);
  }

  $stmt = $conn->prepare("DELETE FROM article WHERE id=?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    echo "<script>alert('Artikel berhasil dihapus!'); window.location.href = 'admin.php?page=article';</script>";
  } else {
    echo "<script>alert('Gagal menghapus artikel!'); window.location.href = 'admin.php?page=article';</script>";
  }
  $stmt->close();
  exit;
}
?>

<div class="container-fluid">
  <h3 class="mb-4"><i class="bi bi-newspaper me-2"></i>Manajemen Artikel</h3>

  <div class="row mb-3">
    <div class="col-md-6 mb-2 mb-md-0">
      <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i> Tambah Article
      </button>
    </div>
    <div class="col-md-6">
      <div class="input-group">
        <input type="text" id="search" class="form-control" placeholder="Cari Artikel (min 3 karakter)...">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover table-bordered align-middle">
      <thead class="table-danger">
        <tr>
          <th scope="col" class="text-center" style="width: 50px;">No</th>
          <th scope="col">Judul</th>
          <th scope="col" style="width: 300px;">Isi</th>
          <th scope="col" class="text-center" style="width: 120px;">Gambar</th>
          <th scope="col" class="text-center" style="width: 120px;">Aksi</th>
        </tr>
      </thead>
      <tbody id="result"></tbody>
    </table>
  </div>

  <div id="loading" class="text-center text-muted" style="display: none;">
    <i class="bi bi-hourglass-split"></i> Memuat data...
  </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalTambahLabel"><i class="bi bi-plus-circle me-2"></i>Tambah Article</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="mb-3">
            <label for="judulTambah" class="form-label">Judul</label>
            <input type="text" class="form-control" id="judulTambah" name="judul" placeholder="Masukkan judul artikel..." required>
          </div>
          <div class="mb-3">
            <label for="isiTambah" class="form-label">Isi</label>
            <textarea class="form-control" id="isiTambah" name="isi" rows="5" placeholder="Tulis isi artikel di sini..." required></textarea>
          </div>
          <div class="mb-3">
            <label for="gambarTambah" class="form-label">Gambar</label>
            <input type="file" class="form-control" id="gambarTambah" name="gambar" accept="image/*">
            <small class="text-muted">Max 500KB (JPG, JPEG, PNG, GIF)</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success" name="simpan"><i class="bi bi-save me-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    loadData();
  });

  function loadData(keyword = '') {
    $.ajax({
      url: "article_search.php",
      type: "POST",
      data: {
        keyword: keyword
      },
      beforeSend: function() {
        $("#loading").show();
      },
      success: function(data) {
        $("#loading").hide();
        $("#result").html(data);
      },
      error: function() {
        $("#loading").hide();
        $("#result").html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data!</td></tr>');
      }
    });
  }

  $("#search").on("keyup", function() {
    var keyword = $(this).val();
    if (keyword.length > 2) {
      loadData(keyword);
    } else if (keyword.length == 0) {
      loadData();
    }
  });
</script>