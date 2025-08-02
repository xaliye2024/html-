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
    $month = $_POST['month'];
    $basicSalary = $_POST['basic_salary'];
    $netSalary = $_POST['net_salary'];
    $paidDate = $_POST['paid_date'];

    if ($employeeName && $employeePhone && $month && $basicSalary && $netSalary && $paidDate) {
        $xaree = $pdo->prepare("INSERT INTO tbl_salary (employee_name, employee_phone, month, basic_salary, net_salary, paid_date) VALUES (?,?,?,?,?,?)");
        if ($xaree->execute([$employeeName, $employeePhone, $month, $basicSalary, $netSalary, $paidDate])) {
            $alertMessage = "✅ Employee salary added successfully.";
            $alertType = "success";
        } else {
            $alertMessage = "❌ Failed to save. Please try again.";
            $alertType = "danger";
        }
    } else {
        $alertMessage = "⚠️ All fields are required.";
        $alertType = "warning";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Employee Salary Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap + Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
    }

    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      width: 220px;
      background-color: #343a40;
      padding-top: 20px;
      color: white;
    }

    .sidebar h4 {
      text-align: center;
      color: #fff;
      margin-bottom: 30px;
    }

    .sidebar a {
      display: block;
      color: white;
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

    .modal-header,
    .table-dark th {
      background-color: #0d6efd;
      color: white;
    }

    .btn-success {
      background-color: #198754;
      border: none;
    }

    .btn-success:hover {
      background-color: #157347;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<!-- <div class="sidebar">
  <h4>HRM System</h4>
  <a href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
  <a href="employee_list.php"><i class="fas fa-users me-2"></i> Shaqaalaha</a>
  <a href="users_list.php"><i class="fas fa-user-shield me-2"></i> Users</a>
  <a href="employee_salary.php" class="bg-primary"><i class="fas fa-money-bill me-2"></i> Mushaharka</a>
  <a href="employee_attendance.php"><i class="fas fa-calendar-check me-2"></i> Attendance</a>
     <a href="report.php"><i class="fas fa-calendar-check me-2"></i> Report</a>
  <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div> -->

<!-- Main Content -->
<div class="main">
  <!-- Alerts -->
  <?php if ($alertMessage): ?>
    <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($alertMessage) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Page Title + Button -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-money-check-alt"></i> Employee Salary Management</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
      <i class="fas fa-user-plus"></i> Add Salary
    </button>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" id="salaryForm" novalidate>
          <div class="modal-header">
            <h5 class="modal-title">Add Employee Salary</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <!-- Fields -->
            <div class="mb-3">
              <label class="form-label">Employee Name</label>
              <input type="text" name="employee_name" class="form-control" required>
              <div class="invalid-feedback">Please enter employee name.</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="tel" name="employee_phone" class="form-control" required>
              <div class="invalid-feedback">Phone number is required.</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Month</label>
              <input type="month" name="month" class="form-control" required>
              <div class="invalid-feedback">Month is required.</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Basic Salary</label>
              <input type="number" name="basic_salary" class="form-control" required>
              <div class="invalid-feedback">Basic salary is required.</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Net Salary</label>
              <input type="number" name="net_salary" class="form-control" required>
              <div class="invalid-feedback">Net salary is required.</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Paid Date</label>
              <input type="date" name="paid_date" class="form-control" required>
              <div class="invalid-feedback">Paid date is required.</div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="btnsave" class="btn btn-success">Save Info</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="table-responsive">
    <table class="table table-striped table-hover mt-4">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Month</th>
          <th>Basic</th>
          <th>Net</th>
          <th>Paid Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $select = $pdo->prepare("SELECT * FROM tbl_salary ORDER BY id DESC");
        $select->execute();
        while ($row = $select->fetch(PDO::FETCH_OBJ)) {
          echo "<tr>
                  <td>{$row->id}</td>
                  <td>{$row->employee_name}</td>
                  <td>{$row->employee_phone}</td>
                  <td>{$row->month}</td>
                  <td>{$row->basic_salary}</td>
                  <td>{$row->net_salary}</td>
                  <td>{$row->paid_date}</td>
                  <td>
                    <a href='delete_employee.php?id={$row->id}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this?')\">
                      <i class='fas fa-trash'></i>
                    </a>
                  </td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
  'use strict';
  const form = document.getElementById('salaryForm');
  form.addEventListener('submit', function (event) {
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    }
    form.classList.add('was-validated');
  }, false);
})();
</script>
</body>
</html>
