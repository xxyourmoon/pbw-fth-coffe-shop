<?php
require_once 'koneksi.php';
$sql = "SELECT * FROM article ORDER BY tanggal DESC";
$hasil = $conn->query($sql);

$sql_gallery = "SELECT * FROM gallery ORDER BY tanggal DESC";
$hasil_gallery = $conn->query($sql_gallery);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="FTH Coffee Shop - Tempat Nongkrong Asik dengan Kopi Nusantara">
  <title>FTH Coffee Shop - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(135deg, #f5f0e8 0%, #e8dcc8 100%);
      min-height: 100vh;
    }

    .hero-section {
      background: linear-gradient(rgba(78, 52, 46, 0.85), rgba(78, 52, 46, 0.85)), url('https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=1200') center/cover;
      color: white;
      padding: 80px 0;
      text-align: center;
    }

    .card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .card-img-top {
      height: 200px;
      object-fit: cover;
    }

    .card-title {
      color: #4e342e;
      font-weight: 600;
    }

    .badge-date {
      background-color: #8d6e63;
    }

    footer {
      background-color: #4e342e;
    }

    .carousel-item img {
      object-fit: cover;
      height: 400px;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #4e342e;">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#"><i class="bi bi-cup-hot-fill me-2"></i>FTH Coffee Shop</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#artikel">Artikel</a></li>
          <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login Admin</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="hero-section">
    <div class="container">
      <h1 class="display-4 fw-bold mb-3"><i class="bi bi-cup-hot-fill"></i> Selamat Datang di FTH Coffee Shop</h1>
      <p class="lead">Nikmati kopi Nusantara terbaik bersama kami</p>
    </div>
  </section>

  <section id="artikel" class="py-5">
    <div class="container">
      <h2 class="text-center mb-2" style="color: #4e342e;"><i class="bi bi-newspaper me-2"></i>Artikel Terbaru</h2>
      <p class="text-center text-muted mb-5">Baca artikel menarik seputar dunia kopi</p>

      <div class="row g-4">
        <?php if ($hasil->num_rows > 0): ?>
          <?php while ($row = $hasil->fetch_assoc()): ?>
            <div class="col-12 col-md-6 col-lg-4">
              <div class="card h-100 shadow">
                <?php if (!empty($row['gambar']) && file_exists('img/' . $row['gambar'])): ?>
                  <img src="img/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['judul']) ?>">
                <?php else: ?>
                  <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                  </div>
                <?php endif; ?>

                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($row['judul']) ?></h5>
                  <p class="card-text text-muted"><?= htmlspecialchars(strlen($row['isi']) > 100 ? substr($row['isi'], 0, 100) . '...' : $row['isi']) ?></p>
                </div>

                <div class="card-footer bg-white border-0">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="badge badge-date text-white"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($row['tanggal'])) ?></span>
                    <small class="text-muted"><i class="bi bi-person me-1"></i><?= htmlspecialchars($row['username']) ?></small>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="col-12">
            <div class="alert alert-info text-center"><i class="bi bi-info-circle me-2"></i>Belum ada artikel yang tersedia.</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section id="gallery" class="py-5 bg-light">
    <div class="container">
      <h2 class="text-center mb-2" style="color: #4e342e;"><i class="bi bi-images me-2"></i>Gallery Kami</h2>
      <p class="text-center text-muted mb-5">Intip suasana dan momen terbaik di FTH Coffee Shop</p>

      <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner rounded shadow">
          <?php
          $active = true;
          if ($hasil_gallery->num_rows > 0):
            while ($row_gallery = $hasil_gallery->fetch_assoc()):
          ?>
              <div class="carousel-item <?= $active ? 'active' : '' ?>">
                <?php if (!empty($row_gallery['gambar']) && file_exists('img/' . $row_gallery['gambar'])): ?>
                  <img src="img/<?= htmlspecialchars($row_gallery['gambar']) ?>" class="d-block w-100" alt="Gallery">
                <?php else: ?>
                  <img src="https://via.placeholder.com/800x400?text=No+Image" class="d-block w-100" alt="No Image">
                <?php endif; ?>
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                  <p><i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($row_gallery['tanggal'])) ?></p>
                </div>
              </div>
            <?php
              $active = false;
            endwhile;
          else:
            ?>
            <div class="carousel-item active">
              <img src="https://via.placeholder.com/800x400?text=Belum+ada+Gallery" class="d-block w-100" alt="Empty Gallery">
            </div>
          <?php endif; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </section>

  <footer class="text-white text-center py-4 mt-auto">
    <div class="container">
      <p class="mb-0">&copy; 2025 FTH Coffee Shop - All Rights Reserved</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php $conn->close(); ?>