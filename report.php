<?php
include_once 'connection.php';
include_once 'navbaar.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] == "admin") {
  header('location:login.php');
  exit();
}

$reportData = [];
$noResults = false;

if (isset($_POST['filter'])) {
  $from = $_POST['from_date'];
  $to = $_POST['to_date'];

  $stmt = $pdo->prepare("SELECT * FROM tbl_salary WHERE paid_date BETWEEN ? AND ? ORDER BY paid_date DESC");
  $stmt->execute([$from, $to]);
  $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($reportData)) {
    $noResults = true;
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HRM Report</title>
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
    }

    body {
      margin: 0;
      background: var(--bg);
      font-family: 'Segoe UI', sans-serif;
    }

    .main-content {
      padding: 30px;
      margin-top: 20px; /* Halkan ayaan hoos uga dhignay 70px -> 20px */
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
  <h2 class="fw-bold mb-4 text-center">Salary Report</h2>

  <form method="POST" class="mb-4 row g-3">
    <div class="col-md-4">
      <label class="form-label">From Date</label>
      <input type="date" name="from_date" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">To Date</label>
      <input type="date" name="to_date" class="form-control" required>
    </div>
    <div class="col-md-4 align-self-end">
      <button type="submit" name="filter" class="btn btn-primary w-100">Filter Report</button>
    </div>
  </form>

  <?php if ($noResults): ?>
    <div class="alert alert-warning">Ma jiro wax mushaar la helay taariikhda aad dooratay.</div>
  <?php elseif (!empty($reportData)): ?>
  <div class="card p-4">
    <h5>Filtered Salary Report</h5>
    <table class="table table-bordered table-striped" id="salaryTable">
      <thead>
        <tr>
          <th>Employee</th>
          <th>Phone</th>
          <th>Month</th>
          <th>Basic</th>
          <th>Net</th>
          <th>Paid Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reportData as $row): ?>
        <tr>
          <td><?= $row['employee_name'] ?></td>
          <td><?= $row['employee_phone'] ?></td>
          <td><?= $row['month'] ?></td>
          <td><?= $row['basic_salary'] ?></td>
          <td><?= $row['net_salary'] ?></td>
          <td><?= $row['paid_date'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    $('#salaryTable').DataTable({
      dom: 'Bfrtip',
      buttons: ['excelHtml5', 'pdfHtml5', 'print']
    });
  });
</script>
</body>
</html>
