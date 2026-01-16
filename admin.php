<?php
session_start();

if (!isset($_SESSION['username'])) {
  header("location:login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - FTH Coffee Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    #content {
      flex: 1;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-sm bg-body-tertiary sticky-top bg-danger-subtle">
    <div class="container">
      <a class="navbar-brand" target="_blank" href="."><i class="bi bi-cup-hot-fill me-2"></i>FTH Coffee Shop</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
          <li class="nav-item"><a class="nav-link" href="admin.php?page=dashboard">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="admin.php?page=article">Article</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-danger fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?= $_SESSION['username'] ?></a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="admin.php?page=profile">Profile</a></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div id="content" class="container py-4">
    <?php
    if (isset($_GET['page'])) {
      $page = $_GET['page'];
      $page = preg_replace('/[^a-zA-Z0-9_]/', '', $page);
      $file = $page . '.php';
      if (file_exists($file)) {
        include $file;
      } else {
        echo '<div class="alert alert-danger">Halaman tidak ditemukan!</div>';
      }
    } else {
      include 'dashboard.php';
    }
    ?>
  </div>

  <footer class="text-center p-3" style="background-color: #4e342e;">
    <div>
      <a href="https://www.instagram.com/fthcoffeeshop"><i class="bi bi-instagram h2 p-2 text-white"></i></a>
      <a href="https://twitter.com/fthcoffeeshop"><i class="bi bi-twitter h2 p-2 text-white"></i></a>
      <a href="https://wa.me/+6281234567890"><i class="bi bi-whatsapp h2 p-2 text-white"></i></a>
    </div>
    <div class="text-white">&copy; 2025 FTH Coffee Shop - All Rights Reserved</div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>