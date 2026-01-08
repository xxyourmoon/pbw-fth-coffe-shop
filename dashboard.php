<?php
require_once 'koneksi.php';

$sql1 = "SELECT * FROM article";
$hasil1 = $conn->query($sql1);
$jumlah_article = $hasil1->num_rows;
?>

<div class="row mb-4">
  <div class="col-12">
    <div class="p-4 rounded-3" style="background: linear-gradient(135deg, #4e342e 0%, #6d4c41 100%);">
      <h3 class="text-white mb-2"><i class="bi bi-cup-hot-fill me-2"></i>Dashboard FTH Coffee Shop</h3>
      <p class="text-white-50 mb-0">Selamat datang kembali, <strong class="text-white"><?= $_SESSION['username'] ?></strong>!</p>
    </div>
  </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
  <div class="col">
    <div class="card border-0 shadow h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-muted mb-1">Total Artikel</h6>
            <h2 class="fw-bold mb-0" style="color: #4e342e;"><?php echo $jumlah_article; ?></h2>
          </div>
          <div class="rounded-circle p-3" style="background-color: rgba(78, 52, 46, 0.1);">
            <i class="bi bi-newspaper fs-3" style="color: #4e342e;"></i>
          </div>
        </div>
        <a href="admin.php?page=article" class="btn btn-sm btn-outline-secondary mt-3"><i class="bi bi-arrow-right me-1"></i>Kelola Artikel</a>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card border-0 shadow h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-muted mb-1">Menu Kopi</h6>
            <h2 class="fw-bold mb-0" style="color: #8d6e63;">15+</h2>
          </div>
          <div class="rounded-circle p-3" style="background-color: rgba(141, 110, 99, 0.1);">
            <i class="bi bi-cup-hot fs-3" style="color: #8d6e63;"></i>
          </div>
        </div>
        <small class="text-muted">Variasi kopi Nusantara</small>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card border-0 shadow h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-muted mb-1">Hari Ini</h6>
            <h2 class="fw-bold mb-0 text-success"><?= date('d M Y') ?></h2>
          </div>
          <div class="rounded-circle p-3" style="background-color: rgba(40, 167, 69, 0.1);">
            <i class="bi bi-calendar-check fs-3 text-success"></i>
          </div>
        </div>
        <small class="text-muted">Semangat bekerja! ☕</small>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-12">
    <div class="card border-0 shadow">
      <div class="card-header bg-white border-0 pt-4">
        <h5 class="mb-0"><i class="bi bi-lightbulb me-2 text-warning"></i>Tips Hari Ini</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 mb-3 mb-md-0">
            <div class="d-flex align-items-start">
              <i class="bi bi-pencil-square fs-4 me-3" style="color: #4e342e;"></i>
              <div>
                <h6 class="mb-1">Tulis Artikel Baru</h6>
                <small class="text-muted">Bagikan tips dan info seputar kopi kepada pengunjung</small>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3 mb-md-0">
            <div class="d-flex align-items-start">
              <i class="bi bi-image fs-4 me-3" style="color: #8d6e63;"></i>
              <div>
                <h6 class="mb-1">Upload Foto Menarik</h6>
                <small class="text-muted">Gambar berkualitas tinggi menarik lebih banyak pembaca</small>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="d-flex align-items-start">
              <i class="bi bi-share fs-4 me-3 text-success"></i>
              <div>
                <h6 class="mb-1">Share ke Media Sosial</h6>
                <small class="text-muted">Promosikan artikel Anda di Instagram dan Twitter</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>