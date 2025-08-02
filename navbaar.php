<?php
include_once 'connection.php';
// include_once 'navbaar.php';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HRM Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
  <style>
    :root {
      --bg: #f0f2f5;
      --fg: #212529;
      --card-bg: #ffffff;
      --accent: #6366f1;
      --sidebar-bg: #343a40;
      --sidebar-text: #ffffff;
    }

    body {
      margin: 0;
      background: var(--bg);
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 220px;
      background-color: var(--sidebar-bg);
      color: var(--sidebar-text);
      padding-top: 30px;
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
      color: #f8f9fa;
    }

    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: var(--sidebar-text);
      text-decoration: none;
    }

    .sidebar a:hover {
      background-color: #495057;
    }

    .submenu {
      padding-left: 30px;
      background-color: #495057;
      display: none;
      transition: all 0.3s ease;
    }

    .submenu a {
      padding: 10px 20px;
      display: block;
      font-size: 0.9rem;
      color: #e2e6ea;
    }

    .sidebar .toggle-arrow {
      float: right;
      transition: transform 0.3s;
    }

    .sidebar .expanded .toggle-arrow {
      transform: rotate(90deg);
    }

    .main-content {
      margin-left: 220px;
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

<div class="sidebar">
  <h4>HRM SYSTEM</h4>
  <a href="dashboard.php"><i class="fas fa-chart-pie me-2"></i> Dashboard</a>
  <a href="employee_list.php"><i class="fas fa-users me-2"></i> Shaqaalaha</a>
  <a href="users_list.php"><i class="fas fa-user-shield me-2"></i> Users</a>
  <a href="employee_salary.php"><i class="fas fa-money-check-alt"></i>Salary Entry</a>
  <a href="Employee_Attendance.php"><i class="fas fa-calendar-check me-2"></i> Attendance</a>
  <a href="dashboard.php" class="report-toggle"><i class="fas fa-money-bill me-2"></i> Report <i class="fas fa-chevron-right toggle-arrow"></i></a>
  <div class="submenu">
    <a href="report.php">Salary Report</a>
    <a href="attendance_report.php">Attendance Report</a>
  </div>
  <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>


<div class="main-content">
  <!-- <h2 class="fw-bold mb-4 text-center">HRM Dashboard</h2> -->
  <div class="row g-4">
    <!-- Your content goes here -->
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.report-toggle');
    const submenu = document.querySelector('.submenu');
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      submenu.style.display = (submenu.style.display === 'block') ? 'none' : 'block';
      toggle.classList.toggle('expanded');
    });
  });
</script>
</body>
</html>
