<?php
include_once 'connection.php';

session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_login'])) {
    $userEmail = $_POST['email'];
    $userPassword = $_POST['password'];

    $select = $pdo->prepare("SELECT * FROM tbl_users WHERE email = :email");
    $select->execute(['email' => $userEmail]);
    $user = $select->fetch(PDO::FETCH_ASSOC);

    if ($user && $userPassword === $user['password']) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Attendance</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at top left, #0f2027, #203a43, #2c5364);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .login-container {
      max-width: 400px;
      margin: auto;
      margin-top: 6%;
      padding: 40px;
      background-color: rgba(0, 0, 0, 0.75);
      border-radius: 15px;
      box-shadow: 0 0 25px rgba(0,0,0,0.6);
      color: white;
    }

    .employee-icon {
      width: 80px;
      margin-bottom: 15px;
    }

    .form-control {
      background-color: #2b2d42;
      border: none;
      color: #fff;
    }

    .form-control::placeholder {
      color: #bbb;
    }

    .btn-login {
      background-color: #ff416c;
      border: none;
    }

    .btn-login:hover {
      background-color: #ff4b2b;
    }

    .alert {
      background-color: rgba(255, 0, 0, 0.1);
      border-left: 4px solid red;
      color: #ff9999;
    }

    .logo-header {
      text-align: center;
      margin-bottom: 25px;
    }

    .logo-header h3 {
      color: #fff;
      font-weight: 600;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="login-container">
    <div class="logo-header">
      <img src="https://cdn-icons-png.flaticon.com/512/219/219983.png" class="employee-icon" alt="Employee Icon">
      <h3><i class="fas fa-user-check"></i> Login</h3>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
      </div>

      <div class="mb-4">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
      </div>

      <div class="d-grid">
        <button type="submit" name="btn_login" class="btn btn-login btn-lg text-white">
          <i class="fa-solid fa-right-to-bracket"></i> Login
        </button>
      </div>
    </form>

    <p class="text-center text-muted small mt-4 mb-0">© <?= date('Y') ?> Employee management System</p>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
