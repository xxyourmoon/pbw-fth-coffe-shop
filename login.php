<?php
session_start();
include "koneksi.php";

if (isset($_SESSION['username'])) {
  header("location:admin.php");
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userInput = $_POST['username'] ?? '';
  $passInput = $_POST['password'] ?? '';
  $username = $userInput;
  $password = md5($passInput);

  $stmt = $conn->prepare("SELECT * FROM user WHERE username=? AND password=?");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $hasil = $stmt->get_result();
  $row = $hasil->fetch_array(MYSQLI_ASSOC);

  if (!empty($row)) {
    $_SESSION['username'] = $row['username'];
    header("location:admin.php");
    exit;
  } else {
    $error = 'Username atau password salah!';
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - FTH Coffee Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #4e342e 0%, #6d4c41 50%, #8d6e63 100%);
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 40px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }

    .login-card .logo {
      font-size: 3rem;
      color: #4e342e;
      margin-bottom: 10px;
    }

    .login-card h1 {
      color: #4e342e;
      font-weight: 700;
      font-size: 1.8rem;
      margin-bottom: 5px;
    }

    .login-card .subtitle {
      color: #8d6e63;
      font-size: 0.9rem;
      margin-bottom: 30px;
    }

    .form-control {
      border-radius: 10px;
      padding: 12px 15px;
      border: 2px solid #e0e0e0;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #8d6e63;
      box-shadow: 0 0 0 0.2rem rgba(141, 110, 99, 0.25);
    }

    .btn-login {
      background: linear-gradient(135deg, #4e342e 0%, #6d4c41 100%);
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      background: linear-gradient(135deg, #3e2723 0%, #5d4037 100%);
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(78, 52, 46, 0.4);
    }

    .alert-error {
      background-color: #ffebee;
      border: 1px solid #ef5350;
      color: #c62828;
      border-radius: 10px;
      padding: 12px;
      margin-bottom: 20px;
    }

    .back-link {
      color: #8d6e63;
      text-decoration: none;
      font-size: 0.9rem;
      transition: color 0.3s;
    }

    .back-link:hover {
      color: #4e342e;
    }
  </style>
</head>

<body>
  <div class="login-card text-center">
    <div class="logo"><i class="bi bi-cup-hot-fill"></i></div>
    <h1>FTH Coffee Shop</h1>
    <p class="subtitle">Login untuk mengakses Dashboard Admin</p>

    <?php if (!empty($error)): ?>
      <div class="alert-error"><i class="bi bi-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3 text-start">
        <label for="username" class="form-label fw-medium">Username</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-person text-secondary"></i></span>
          <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required>
        </div>
      </div>

      <div class="mb-4 text-start">
        <label for="password" class="form-label fw-medium">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-lock text-secondary"></i></span>
          <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
      </div>

      <button type="submit" class="btn btn-login text-white w-100 mb-3"><i class="bi bi-box-arrow-in-right me-2"></i>Login</button>
    </form>

    <a href="index.php" class="back-link"><i class="bi bi-arrow-left me-1"></i>Kembali ke Halaman Utama</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>