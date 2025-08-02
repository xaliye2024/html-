<?php
include_once 'connection.php';
include_once 'navbaar.php';
session_start();

if (empty($_SESSION['email'])) {
    header('location:login.php');
    exit();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] == "User") {
    header('location:login.php');
    exit();
}

$alertMessage = '';
$alertType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnsave'])) {
    $userName = trim($_POST['username']);
    $userEmail = trim($_POST['email']);
    $userPassword = trim($_POST['password']);
    $userRole = $_POST['role'];

    if ($userName && $userEmail && $userPassword && $userRole) {
        $stmt = $pdo->prepare("INSERT INTO tbl_users (username, email, password, role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$userName, $userEmail, $userPassword, $userRole])) {
            $alertMessage = "User has been added successfully!";
            $alertType = "success";
        } else {
            $alertMessage = "Something went wrong. Please try again.";
            $alertType = "danger";
        }
    } else {
        $alertMessage = "All fields are required.";
        $alertType = "warning";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Users</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 220px;
      height: 100vh;
      background-color: #343a40;
      color: white;
      padding-top: 20px;
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 20px;
      color: #fff;
    }

    .sidebar a {
      display: block;
      color: #fff;
      padding: 12px 20px;
      text-decoration: none;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background-color: #495057;
    }

    .main {
      margin-left: 220px;
      padding: 20px;
    }

    .saveButton {
      background: linear-gradient(90deg, #4f46e5, #3b82f6);
      color: white;
      border: none;
      transition: 0.3s ease;
    }

    .saveButton:hover {
      background: #3b82f6;
      transform: scale(1.03);
    }

    .alert-dismissible .btn-close {
      position: absolute;
      top: 0.7rem;
      right: 1rem;
    }

    h1 {
      color: #0d6efd;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<!-- <div class="sidebar">
  <h4>HRM System</h4>
  <a href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
  <a href="employee_list.php"><i class="fas fa-users me-2"></i> Shaqaalaha</a>
  <a href="users_list.php"><i class="fas fa-money-bill me-2"></i>Users</a>
  <a href=" employee_salary.php "><i class="fas fa-calendar-check me-2"></i> Mushaharka</a>
  <a href="attendance.php" class="bg-primary text-white"><i class="fas fa-user-shield me-2"></i>Attendance </a>
  <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div> -->

<!-- Main Content -->
<div class="main">
  <h1 class="text-center fw-bold mb-4">💼 Users</h1>

  <!-- Alert Message -->
  <?php if (!empty($alertMessage)): ?>
    <div class="alert alert-<?= htmlspecialchars($alertType) ?> alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($alertMessage) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-md-4">
      <form method="post" id="userForm" novalidate>
        <div class="mb-3">
          <label class="form-label">Username:</label>
          <input type="text" name="username" class="form-control" required />
          <div class="invalid-feedback">Username is required.</div>
        </div>

        <div class="mb-3">
          <label class="form-label">Email:</label>
          <input type="email" name="email" class="form-control" required />
          <div class="invalid-feedback">Valid email is required.</div>
        </div>

        <div class="mb-3">
          <label class="form-label">Password:</label>
          <input type="text" name="password" class="form-control" required />
          <div class="invalid-feedback">Password is required.</div>
        </div>

        <div class="mb-3">
          <label class="form-label">Role:</label>
          <select name="role" class="form-select" required>
            <option disabled selected value="">Select Role</option>
            <option value="Admin">Admin</option>
            <option value="User">User</option>
          </select>
          <div class="invalid-feedback">Please select a role.</div>
        </div>

        <div class="text-center">
          <button type="submit" class="btn saveButton px-4" name="btnsave">Save Info</button>
        </div>
      </form>
    </div>

    <div class="col-md-8 mt-4 mt-md-0">
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-primary text-center">
            <tr>
              <th>#</th>
              <th>Username</th>
              <th>Email</th>
              <th>Password</th>
              <th>Role</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <?php
            $select = $pdo->prepare("SELECT * FROM tbl_users ORDER BY id DESC");
            $select->execute();
            foreach ($select->fetchAll(PDO::FETCH_OBJ) as $user) {
              echo "<tr>
                      <td>{$user->id}</td>
                      <td>{$user->username}</td>
                      <td>{$user->email}</td>
                      <td>{$user->password}</td>
                      <td>{$user->role}</td>
                    </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap + Form Validation -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
  const form = document.getElementById('userForm');
  form.addEventListener('submit', function (e) {
    if (!form.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
    }
    form.classList.add('was-validated');
  }, false);
})();
</script>
</body>
</html>
