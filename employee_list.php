<?php
session_start();
include_once 'connection.php';
include_once 'navbaar.php';

if (!isset($_SESSION['email']) || $_SESSION['email'] == "") {
    header('Location: login.php');
    exit();
}

$alertMessage = '';
$alertType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnsave'])) {
    $employeeName = $_POST['employee_name'];
    $employeePhone = $_POST['employee_phone'];
    $employeeStatus = $_POST['employee_status'];
    $date = $_POST['date'];

    $check = $pdo->prepare("SELECT * FROM tbl_attendance WHERE employee_name = ? AND date = ?");
    $check->execute([$employeeName, $date]);

    if ($check->rowCount() > 0) {
        $alertMessage = "Attendance already recorded for this employee on this date.";
        $alertType = "warning";
    } else {
        $insert = $pdo->prepare("INSERT INTO tbl_attendance (employee_name, employee_phone, attendance_status, date) VALUES (?, ?, ?, ?)");
        if ($insert->execute([$employeeName, $employeePhone, $employeeStatus, $date])) {
            $alertMessage = "Attendance added successfully.";
            $alertType = "success";
        } else {
            $alertMessage = "Failed to save attendance.";
            $alertType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employee Attendance</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

  <style>
    :root {
      --sidebar-bg: #343a40;
      --sidebar-text: #fff;
      --bg: #f0f2f5;
      --accent: #0d6efd;
    }

    body {
      margin: 0;
      background-color: var(--bg);
      font-family: Arial, sans-serif;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 220px;
      background-color: var(--sidebar-bg);
      padding-top: 20px;
    }

    .sidebar a {
      display: block;
      color: var(--sidebar-text);
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

    .center-title {
      text-align: center;
      font-size: 2rem;
      color: var(--accent);
      font-weight: bold;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<!-- <div class="sidebar">
  <h4 class="text-center text-white mb-4">HRM System</h4>
  <a href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
  <a href="employee_list.php"><i class="fas fa-users me-2"></i> Shaqaalaha</a>
  <a href="users_list.php"><i class="fas fa-user-shield me-2"></i> Users</a>
  <a href="employee_salary.php"><i class="fas fa-money-bill me-2"></i> Mushaharka</a>
  <a href="employee_attendance.php" class="bg-primary text-white"><i class="fas fa-calendar-check me-2"></i> Attendance</a>
   <a href="report.php"><i class="fas fa-calendar-check me-2"></i> Report</a>
  <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div> -->

<div class="main">
  <?php if (!empty($alertMessage)): ?>
    <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
      <?= $alertMessage ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <h1 class="center-title">Employee Attendance</h1>

  <div class="d-flex justify-content-between mb-3">
    <div class="d-flex gap-3">
      <input type="date" id="filterDate" class="form-control" />
      <select id="filterStatus" class="form-select">
        <option value="">All Status</option>
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
        <option value="Leave">Leave</option>
      </select>
    </div>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
      <i class="fas fa-plus"></i> Add Attendance
    </button>
  </div>

  <!-- Modal Form -->
  <div class="modal fade" id="addEmployeeModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" id="attendanceForm" novalidate>
          <div class="modal-header">
            <h5 class="modal-title">Add Attendance</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Employee Name</label>
              <input type="text" name="employee_name" class="form-control" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="tel" name="employee_phone" class="form-control" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Status</label>
              <select name="employee_status" class="form-select" required>
                <option value="">Select status</option>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Leave">Leave</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Date</label>
              <input type="date" name="date" class="form-control" required />
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="btnsave" class="btn btn-success">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Attendance Table -->
  <div class="table-responsive mt-4">
    <table class="table table-bordered text-center" id="attendanceTable">
      <thead class="table-light">
        <tr>
          <th>ID</th><th>Name</th><th>Phone</th><th>Status</th><th>Date</th><th>Edit</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $select = $pdo->prepare("SELECT * FROM tbl_attendance WHERE date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ORDER BY date DESC");
        $select->execute();
        while ($row = $select->fetch(PDO::FETCH_OBJ)) {
          echo "<tr>
                  <td>{$row->id}</td>
                  <td>" . htmlspecialchars($row->employee_name) . "</td>
                  <td>" . htmlspecialchars($row->employee_phone) . "</td>
                  <td>{$row->attendance_status}</td>
                  <td>{$row->date}</td>
                  <td><a href='edit_attendance.php?id={$row->id}' class='btn btn-sm btn-primary'>
                      <i class='fas fa-edit'></i> Edit</a></td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
