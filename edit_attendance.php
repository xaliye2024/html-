
<?php

include_once 'connection.php';
include_once 'navbar.php';
if(isset($_GET['id'])){
  $employeeID = $_GET['id'];
  $select = $pdo->prepare("SELECT * FROM tbl_attendance WHERE id = ?");
  $select->execute([$employeeID]);
  $employee = $select->fetch(PDO::FETCH_OBJ);
} 
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnsave'])){
  $employeeName = $_POST['employee_name'];
   $employeePhone = $_POST['employee_phone'];
    $attendance_status = $_POST['attendance_status'];
  $date = $_POST['date'];

  

     $Update = $pdo->prepare("UPDATE tbl_attendance SET employee_name =?, employee_phone =?,attendance_status =?, date =? WHERE id =?");
     $Update->execute([$employeeName, $employeePhone, $attendance_status, $date, $employeeID]);

      header('location:Employee_Attendance.php');
    }
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    

 <div class="container mt-5">
    <h1 class="text-center text-primary fw-bold display-5 mb-4">
        💼 Edit employee
    </h1>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <form method="post" class="p-4 border rounded shadow-sm bg-light" id="employeeForm">
                <h4 class="text-center mb-4 text-primary">✏️ Update Employee Info</h4>

                <!-- 1. Employee Name -->
                <div class="form-group mb-3">
                    <label class="fw-bold">1. Employee Name:</label>
                    <input type="text" name="employee_name" id="employee_name" value="<?php echo $employee->employee_name ?>" class="form-control" placeholder="Enter employee name" required>
                    <div class="text-danger small mt-1" id="nameError"></div>
                </div>

                <!-- 2. Phone -->
                <div class="form-group mb-3">
                    <label class="fw-bold">2. Employee Phone:</label>
                    <input type="number" name="employee_phone" id="employee_phone" value="<?php echo $employee->employee_phone ?>" class="form-control" placeholder="Enter employee phone" required>
                    <div class="text-danger small mt-1" id="phoneError"></div>
                </div>

               

                

                   <!-- 4. Position -->
                <div class="form-group mb-4">
                    <label class="fw-bold">4. Attendance Status:</label>
                    <select name="attendance_status" class="form-control" required>
                        <option disabled selected value="">Select Attendance Status</option>
                        <option value="Present" <?php if ($employee->attendance_status == 'Present') echo 'selected'; ?>>Present</option>
                        <option value="Absent" <?php if ($employee->attendance_status == 'Absent') echo 'selected'; ?>>Absent</option>
                        <option value="Leave" <?php if ($employee->attendance_status == 'Leave') echo 'selected'; ?>>Leave</option>
                    </select>
                </div> 

                <div class="form-group mb-3">
                    <label class="fw-bold">3.date:</label>
                    <input type="date" name="date" id="date" value="<?php echo $employee->date ?>" class="form-control" placeholder="Enter date" required>
                    <div class="text-danger small mt-1" id="dateError"></div>
                </div>


                <!-- Submit -->
                <div class="text-center">
                    <button type="submit" name="btnsave" class="btn btn-success px-4">💾 Save Changes</button>
                </div>
            </form>

        </div>
    </div>
</div>

    
    
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
     <script>
    // Realtime validation
    document.getElementById('employee_name').addEventListener('input', function () {
        const name = this.value.trim();
        document.getElementById('nameError').textContent = name.length < 3 ? "Name must be at least 3 characters" : "";
    });

    document.getElementById('employee_phone').addEventListener('input', function () {
        const phone = this.value.trim();
        document.getElementById('phoneError').textContent = phone.length < 7 ? "Phone number too short" : "";
    });

    document.getElementById('date').addEventListener('input', function () {
        const email = this.value.trim();
        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(date);
        document.getElementById('dateError').textContent = !valid ? "Invalid date" : "";
    });
</script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>