<?php
// session_start();
include_once 'connection.php';
include_once 'navbaar.php';

$alertMessage = '';
$alertType = '';

// if (!isset($_SESSION['email']) || $_SESSION['email'] == "") {
//     header('Location: login.php');
//     exit();
// }

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
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
  <!-- DataTables Buttons CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />

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
      z-index: 1000;
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

    .main-content {
      margin-left: 220px;
      padding: 30px 20px;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }
      .main-content {
        margin-left: 0;
      }
    }

    .center-title {
      text-align: center;
      font-size: 2rem;
      color: #0d6efd;
      margin-bottom: 20px;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <!-- <div class="sidebar">
    <h4>HRM SYSTEM</h4>
    <a href="dashboard.php"><i class="fas fa-chart-pie me-2"></i> Dashboard</a>
    <a href="employee_list.php"><i class="fas fa-users me-2"></i> Shaqaalaha</a>
    <a href="users_list.php"><i class="fas fa-user-shield me-2"></i> Users</a>
    <a href="employee_salary.php"><i class="fas fa-hand-holding-usd me-2"></i> Mushaharka</a>
    <a href="Employee_Attendance.php"><i class="fas fa-calendar-check me-2"></i> Attendance</a>
     <a href="report.php"><i class="fas fa-calendar-check me-2"></i> Report</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
  </div> -->

  <!-- Main Content -->
  <div class="main-content">

    <?php if (!empty($alertMessage)): ?>
      <div class="alert alert-<?= htmlspecialchars($alertType) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($alertMessage) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <h1 class="center-title">Employee Attendance</h1>

    <!-- Filters -->
    <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">
      <input type="date" id="filterDate" class="form-control" style="max-width: 250px;" />
      <select id="filterStatus" class="form-select" style="max-width: 250px;">
        <option value="">All Status</option>
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
        <option value="Leave">Leave</option>
      </select>
    </div>

    <!-- Add Attendance Button -->
    <div class="d-flex justify-content-end mb-3">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
        <i class="fas fa-user-plus"></i> Add Attendance
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
                <div class="invalid-feedback">Please enter employee name.</div>
              </div>
              <div class="mb-3">
                <label class="form-label">Employee Phone</label>
                <input type="tel" name="employee_phone" class="form-control" required />
                <div class="invalid-feedback">Please enter phone number.</div>
              </div>
              <div class="mb-3">
                <label class="form-label">Attendance Status</label>
                <select name="employee_status" class="form-control" required>
                  <option value="" disabled selected>Select status</option>
                  <option value="Present">Present</option>
                  <option value="Absent">Absent</option>
                  <option value="Leave">Leave</option>
                </select>
                <div class="invalid-feedback">Please select attendance status.</div>
              </div>
              <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" required />
                <div class="invalid-feedback">Please select date.</div>
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

    <!-- Attendance Table -->
    <div class="card shadow mb-4">
      <div class="card-body table-responsive">
        <table class="table table-bordered" id="attendanceTable" width="100%" cellspacing="0">
          <thead class="table-light text-center">
            <tr>
              <th>ID</th><th>Name</th><th>Phone</th><th>Status</th><th>Date</th><th>Edit</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <?php
            $select = $pdo->prepare("SELECT * FROM tbl_attendance WHERE date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ORDER BY date DESC");
            $select->execute();
            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
              echo "<tr>
                      <td>{$row->id}</td>
                      <td>" . htmlspecialchars($row->employee_name) . "</td>
                      <td>" . htmlspecialchars($row->employee_phone) . "</td>
                      <td>" . htmlspecialchars($row->attendance_status) . "</td>
                      <td>{$row->date}</td>
                      <td><a href='edit_attendance.php?id={$row->id}' class='btn btn-sm btn-primary'>
                            <i class='fa-solid fa-pen-to-square'></i> Edit</a></td>
                    </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Monthly Attendance Report -->
    <div class="card shadow mb-4">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">Monthly Attendance Report (Last 30 Days per Employee)</h5>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-bordered text-center" id="monthlyReportTable">
          <thead class="table-light">
            <tr>
              <th>Employee Name</th><th>Year</th><th>Month</th><th>Days Present</th><th>Days Absent</th><th>Days on Leave</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $monthlyReport = $pdo->prepare("
              SELECT employee_name, YEAR(date) AS year, MONTH(date) AS month,
                     SUM(CASE WHEN attendance_status = 'Present' THEN 1 ELSE 0 END) AS present_days,
                     SUM(CASE WHEN attendance_status = 'Absent' THEN 1 ELSE 0 END) AS absent_days,
                     SUM(CASE WHEN attendance_status = 'Leave' THEN 1 ELSE 0 END) AS leave_days
              FROM tbl_attendance
              WHERE date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
              GROUP BY employee_name, YEAR(date), MONTH(date)
              ORDER BY employee_name, year, month");
            $monthlyReport->execute();

            while ($row = $monthlyReport->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>
                      <td>" . htmlspecialchars($row['employee_name']) . "</td>
                      <td>{$row['year']}</td>
                      <td>{$row['month']}</td>
                      <td>{$row['present_days']}</td>
                      <td>{$row['absent_days']}</td>
                      <td>{$row['leave_days']}</td>
                    </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

  </div> <!-- end main-content -->

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function () {
      var table = $('#attendanceTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['excelHtml5', 'pdfHtml5']
      });

      $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var filterDate = $('#filterDate').val();
        var filterStatus = $('#filterStatus').val();
        var date = data[4];
        var status = data[3];

        if ((filterDate === "" || date === filterDate) &&
            (filterStatus === "" || status === filterStatus)) {
          return true;
        }
        return false;
      });

      $('#filterDate, #filterStatus').on('change', function () {
        table.draw();
      });

      $('#attendanceForm').on('submit', function(e) {
        if (!this.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        $(this).addClass('was-validated');
      });
    });
  </script>

</body>
</html>
