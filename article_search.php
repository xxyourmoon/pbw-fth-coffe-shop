<?php
require_once 'koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';

if (!empty($keyword)) {
  $sql = "SELECT * FROM article WHERE judul LIKE ? OR isi LIKE ? OR tanggal LIKE ? OR username LIKE ? ORDER BY tanggal DESC";
  $stmt = $conn->prepare($sql);
  $search = "%" . $keyword . "%";
  $stmt->bind_param("ssss", $search, $search, $search, $search);
  $stmt->execute();
  $hasil = $stmt->get_result();
} else {
  $sql = "SELECT * FROM article ORDER BY tanggal DESC";
  $hasil = $conn->query($sql);
}

if ($hasil->num_rows > 0):
  $no = 1;
  while ($row = $hasil->fetch_assoc()):
?>
    <tr>
      <td class="text-center"><?= $no++ ?></td>
      <td>
        <strong><?= htmlspecialchars($row['judul']) ?></strong><br>
        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y H:i', strtotime($row['tanggal'])) ?> &nbsp;|&nbsp; <i class="bi bi-person me-1"></i><?= htmlspecialchars($row['username']) ?></small>
      </td>
      <td><small><?= htmlspecialchars(strlen($row['isi']) > 100 ? substr($row['isi'], 0, 100) . '...' : $row['isi']) ?></small></td>
      <td class="text-center">
        <?php if (!empty($row['gambar']) && file_exists('img/' . $row['gambar'])): ?>
          <img src="img/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>" class="img-thumbnail" style="max-width: 80px; max-height: 60px; object-fit: cover;">
        <?php else: ?>
          <span class="badge bg-secondary">No Image</span>
        <?php endif; ?>
      </td>
      <td class="text-center">
        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>" title="Edit"><i class="bi bi-pencil"></i></button>
        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id'] ?>" title="Hapus"><i class="bi bi-x-circle"></i></button>
      </td>
    </tr>

    <div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1" aria-labelledby="modalEditLabel<?= $row['id'] ?>" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="modalEditLabel<?= $row['id'] ?>"><i class="bi bi-pencil-square me-2"></i>Edit Article</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="article.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <input type="hidden" name="gambar_lama" value="<?= $row['gambar'] ?>">
              <div class="mb-3">
                <label for="judul<?= $row['id'] ?>" class="form-label">Judul</label>
                <input type="text" class="form-control" id="judul<?= $row['id'] ?>" name="judul" value="<?= htmlspecialchars($row['judul']) ?>" required>
              </div>
              <div class="mb-3">
                <label for="isi<?= $row['id'] ?>" class="form-label">Isi</label>
                <textarea class="form-control" id="isi<?= $row['id'] ?>" name="isi" rows="5" required><?= htmlspecialchars($row['isi']) ?></textarea>
              </div>
              <div class="mb-3">
                <label for="gambar<?= $row['id'] ?>" class="form-label">Gambar</label>
                <?php if (!empty($row['gambar']) && file_exists('img/' . $row['gambar'])): ?>
                  <div class="mb-2">
                    <img src="img/<?= htmlspecialchars($row['gambar']) ?>" alt="Current Image" class="img-thumbnail" style="max-width: 150px;">
                    <p class="text-muted small">Gambar saat ini: <?= $row['gambar'] ?></p>
                  </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="gambar<?= $row['id'] ?>" name="gambar" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar. Max 500KB (JPG, PNG, GIF)</small>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-warning" name="simpan"><i class="bi bi-save me-1"></i>Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalHapus<?= $row['id'] ?>" tabindex="-1" aria-labelledby="modalHapusLabel<?= $row['id'] ?>" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="modalHapusLabel<?= $row['id'] ?>"><i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="article.php" method="POST">
            <div class="modal-body">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <input type="hidden" name="gambar" value="<?= $row['gambar'] ?>">
              <p class="mb-0">Yakin menghapus artikel <strong>"<?= htmlspecialchars($row['judul']) ?>"</strong>?</p>
              <p class="text-muted small mt-2">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-danger" name="hapus"><i class="bi bi-trash me-1"></i>Hapus</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  <?php
  endwhile;
else:
  ?>
  <tr>
    <td colspan="5" class="text-center text-muted py-4">
      <i class="bi bi-inbox fs-1 d-block mb-2"></i>
      <?php if (!empty($keyword)): ?>
        Tidak ditemukan artikel dengan kata kunci "<strong><?= htmlspecialchars($keyword) ?></strong>"
      <?php else: ?>
        Belum ada artikel yang tersedia.
      <?php endif; ?>
    </td>
  </tr>
<?php endif; ?>