<?php
require_once 'koneksi.php';
require_once 'upload_foto.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$id_user = $_SESSION['id_user'] ?? 0;
$username = $_SESSION['username'];
$foto_lama = '';

if ($id_user == 0 && !empty($username)) {
  $stmt = $conn->prepare("SELECT id, foto FROM user WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    $id_user = $row['id'];
    $foto_lama = $row['foto'];
    $_SESSION['id_user'] = $id_user;
    if (!isset($_SESSION['foto'])) {
      $_SESSION['foto'] = $foto_lama;
    }
  }
  $stmt->close();
} else {
  $stmt = $conn->prepare("SELECT foto FROM user WHERE id = ?");
  $stmt->bind_param("i", $id_user);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    $foto_lama = $row['foto'];
  }
  $stmt->close();
}

if (isset($_POST['simpan'])) {
  $password_baru = $_POST['password'];
  $foto = $foto_lama;

  if (!empty($password_baru)) {
    $password_hash = md5($password_baru);
    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $password_hash, $id_user);
    $stmt->execute();
    $stmt->close();
  }

  if (!empty($_FILES['foto']['name'])) {
    if (!empty($foto_lama) && file_exists('img/' . $foto_lama)) {
      unlink('img/' . $foto_lama);
    }
    $upload = upload_foto($_FILES['foto']);
    if ($upload['status']) {
      $foto = $upload['message'];

      $stmt = $conn->prepare("UPDATE user SET foto = ? WHERE id = ?");
      $stmt->bind_param("si", $foto, $id_user);
      $stmt->execute();
      $stmt->close();

      $_SESSION['foto'] = $foto;
    } else {
      echo "<script>alert('Gagal upload foto: " . $upload['message'] . "'); window.location.href = 'admin.php?page=profile';</script>";
      exit;
    }
  }

  echo "<script>alert('Profile berhasil diupdate!'); window.location.href = 'admin.php?page=profile';</script>";
  exit;
}
?>

<div class="container-fluid">
  <h3 class="mb-4"><i class="bi bi-person-circle me-2"></i>Profile User</h3>

  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h5 class="card-title mb-0">Ubah Profile</h5>
        </div>
        <div class="card-body">
          <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>" readonly>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Ganti Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Tuliskan password baru jika ingin mengganti...">
            </div>

            <div class="mb-3">
              <label for="foto" class="form-label">Ganti Foto Profil</label>
              <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
            </div>

            <div class="mb-3 text-center">
              <label class="form-label d-block text-start">Foto Profil Saat Ini</label>
              <?php if (!empty($foto_lama) && file_exists('img/' . $foto_lama)): ?>
                <img src="img/<?= htmlspecialchars($foto_lama) ?>" alt="Profile" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
              <?php else: ?>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($username) ?>&background=random" alt="Default Profile" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
              <?php endif; ?>
            </div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary" name="simpan"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>