<?php
session_start();
include_once 'connection.php';
include_once 'navbaar.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] == "User") {
  header('location:login.php');
  exit();
}

$employee = $pdo->query("SELECT COUNT(*) FROM tbl_hrm")->fetchColumn();
$employee_salary = $pdo->query("SELECT COUNT(*) FROM tbl_salary")->fetchColumn();
$attendance = $pdo->query("SELECT COUNT(*) FROM tbl_attendance")->fetchColumn();
$users = $pdo->query("SELECT COUNT(*) FROM tbl_users")->fetchColumn();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HRM Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --bg: #f0f2f5;
      --fg: #212529;
      --card-bg: #ffffff;
      --accent: #6366f1;
    }

    body {
      margin: 0;
      background: var(--bg);
      font-family: 'Segoe UI', sans-serif;
    }

    .main-content {
      padding: 30px;
    }

    .card-custom {
      background: linear-gradient(135deg, var(--accent), #00c6ff);
      color: white;
      border-radius: 15px;
      text-align: center;
      padding: 20px;
      transition: transform 0.3s ease;
    }

    .card-custom:hover {
      transform: scale(1.03);
      box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }

    .card-icon {
      font-size: 40px;
      margin-bottom: 10px;
    }

    .card-title {
      font-size: 1rem;
      text-transform: uppercase;
    }

    .card-count {
      font-size: 2rem;
      font-weight: bold;
    }
  </style>
</head>

<body>
<div class="main-content">
  <h2 class="fw-bold mb-4 text-center">HRM Dashboard</h2>
  <div class="row g-4">
    <?php
    $tiles = [
      ['icon' => 'fas fa-users', 'label' => 'Shaqaalaha', 'count' => $employee, 'link' => 'employee_list.php'],
      ['icon' => 'fas fa-user-shield', 'label' => 'Users', 'count' => $users, 'link' => 'users_list.php'],
      ['icon' => 'fas fa-hand-holding-usd', 'label' => 'Mushaharka', 'count' => $employee_salary, 'link' => 'employee_salary.php'],
      ['icon' => 'fas fa-calendar-check', 'label' => 'Imanasho', 'count' => $attendance, 'link' => 'Employee_Attendance.php'],
    ];

    foreach ($tiles as $tile): ?>
      <div class="col-sm-6 col-lg-3">
        <a href="<?= $tile['link'] ?>" class="text-decoration-none">
          <div class="card-custom">
            <i class="<?= $tile['icon'] ?> card-icon"></i>
            <div class="card-title"><?= $tile['label'] ?></div>
            <div class="card-count"><?= $tile['count'] ?></div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="card mt-5 p-4" style="background: var(--card-bg);">
    <canvas id="employeeChart"></canvas>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('employeeChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Shaqaalaha', 'Users', 'Mushaharka', 'Attendance'],
      datasets: [{
        label: 'Tirada',
        data: [<?= $employee ?>, <?= $users ?>, <?= $employee_salary ?>, <?= $attendance ?>],
        backgroundColor: ['#6366f1','#22c55e','#f59e0b','#ef4444']
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
</body>
</html>
