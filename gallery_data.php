<?php
require_once 'koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';

if (!empty($keyword)) {
  $search = "%" . $keyword . "%";
  $sql = "SELECT * FROM gallery WHERE tanggal LIKE ? OR username LIKE ? ORDER BY tanggal DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $search, $search);
  $stmt->execute();
  $hasil = $stmt->get_result();
} else {
  $sql = "SELECT * FROM gallery ORDER BY tanggal DESC";
  $hasil = $conn->query($sql);
}

if ($hasil->num_rows > 0):
  $no = 1;
  while ($row = $hasil->fetch_assoc()):
?>
    <tr>
      <td class="text-center"><?= $no++ ?></td>
      <td class="text-center">
        <?= date('d M Y H:i', strtotime($row['tanggal'])) ?>
        <br>
        <small class="text-muted">by: <?= htmlspecialchars($row['username']) ?></small>
      </td>
      <td class="text-center">
        <?php if (!empty($row['gambar']) && file_exists('img/' . $row['gambar'])): ?>
          <img src="img/<?= htmlspecialchars($row['gambar']) ?>" alt="Gallery Image" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
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
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="modalEditLabel<?= $row['id'] ?>"><i class="bi bi-pencil-square me-2"></i>Edit Gallery</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="gallery.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <input type="hidden" name="gambar_lama" value="<?= $row['gambar'] ?>">
              <div class="mb-3">
                <label for="gambar<?= $row['id'] ?>" class="form-label">Gambar</label>
                <?php if (!empty($row['gambar']) && file_exists('img/' . $row['gambar'])): ?>
                  <div class="mb-2">
                    <img src="img/<?= htmlspecialchars($row['gambar']) ?>" alt="Current Image" class="img-thumbnail" style="max-width: 150px;">
                  </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="gambar<?= $row['id'] ?>" name="gambar" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
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
          <form action="gallery.php" method="POST">
            <div class="modal-body">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <input type="hidden" name="gambar" value="<?= $row['gambar'] ?>">
              <p class="mb-0">Yakin menghapus gallery ini?</p>
              <div class="mt-2 text-center">
                <?php if (!empty($row['gambar']) && file_exists('img/' . $row['gambar'])): ?>
                  <img src="img/<?= htmlspecialchars($row['gambar']) ?>" alt="Preview" class="img-thumbnail" style="max-width: 150px;">
                <?php endif; ?>
              </div>
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
    <td colspan="4" class="text-center text-muted py-4">
      <i class="bi bi-images fs-1 d-block mb-2"></i>
      <?php if (!empty($keyword)): ?>
        Tidak ditemukan gallery dengan kata kunci "<strong><?= htmlspecialchars($keyword) ?></strong>"
      <?php else: ?>
        Belum ada data gallery.
      <?php endif; ?>
    </td>
  </tr>
<?php endif; ?>