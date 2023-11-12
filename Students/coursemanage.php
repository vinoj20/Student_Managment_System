<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
$reg = $_SESSION['RegNumber'];

if (isset($_POST['select'])) {
  $year = $_POST['year'];
  $semester = $_POST['semester'];
  $Q = "Select code,Name from tblsubject where year='$year' and semester='$semester'";
  $result = $conn->query($Q);
  $num = $result->num_rows;
  if ($num > 0) {
    $check = 1;
  } else {
    $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>No course available </div>";
  }


}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/seu_logo.png" rel="icon">
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
    setTimeout(function(){
  removeMessage(window.location.href);
} , 2000);

function removeMessage(url) {
  
  // Select the element with ID 'msg'
  var MsgElement = document.querySelector('#msg');
  // Check if the element exists
  if (MsgElement) {
    
    // If the element exists, remove it from the DOM
    MsgElement.remove();
    var url = window.location.href;
    var cleanUrl = url.split("?")[0]; // Get the URL without the query parameters
    window.location.href = cleanUrl; // Redirect to the clean URL
  }
}
  </script>
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">All Courses</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Courses</li>
            </ol>
          </div>

          <!-- All enrolled Courses-->
          <?php include "enrolledcourses.php"; ?>
          <!-- All enrolled Courses-->

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Select Year and Semester</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Year<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * from tblyear";
                        $result = $conn->query($qry);
                        echo ' <select required name="year"  onchange="classArmDropdown(document.querySelector(\'select[name=semester]\',this.value)" class="form-control mb-3" >';
                        echo '<option value="non">--Select year--</option>';
                        while ($rows = $result->fetch_assoc()) {
                          echo '<option value="' . $rows['Id'] . '" >' . $rows['Year'] . '</option>';

                        }
                        echo '</select>';

                        ?>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Semester<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * from tblsemester";
                        $result = $conn->query($qry);
                        echo ' <select required name="semester" onchange="classArmDropdown(this.value,document.querySelector(\'select[name=year]\').value)" class="form-control mb-3">';
                        echo '<option value="non">--Select semester--</option>';
                        while ($rows = $result->fetch_assoc()) {
                          echo '<option value="' . $rows['Id'] . '" >' . $rows['SemesterName'] . '</option>';

                        }
                        echo '</select>';

                        ?>
                      </div>
                      <!-- <div class="col-xl-6">
                        
                        </div> -->
                    </div>
                    <button type="submit" name="select" class="btn btn-primary">Select</button>
                  </form>
                </div>
              </div>

              <?php if ($check) { ?>
                <div class="row">
                  <div class="col-lg-12">
                    <!-- Form Basic -->
                    <div class="card mb-4">
                      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary" style="font-size:30px;">All Courses</h6>
                        <?php echo $statusMsg; ?>
                      </div>
                      <div class="card-body">
                        <?php
                        $Q = "Select id,code,Name from tblsubject where year='$year' and semester='$semester'";
                        $result = $conn->query($Q);

                        echo '<ul class="m-0 font-weight-bold text-primary" style="font-size:25px;">';
                        while ($rows = $result->fetch_assoc()) {
                          $N = $rows['code'] . " " . $rows['Name'];
                          echo '<a href="coursedetails.php?Cid=' . $rows['id'] . '&name=' . $N . '"><li>' . $N . '</li></a>';
                        }
                        echo '</ul>';


                        ?>


                      </div>
                    <?php } ?>
                  </div>
                  <!--Row-->

                  <!-- Documentation Link -->
                  <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

                </div>
                <!---Container Fluid-->
              </div>
              <!-- Footer -->
              <?php include "Includes/footer.php"; ?>
              <!-- Footer -->
            </div>
          </div>

          <!-- Scroll to top -->
          <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
          </a>

          <script src="../vendor/jquery/jquery.min.js"></script>
          <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
          <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
          <script src="js/ruang-admin.min.js"></script>
          <!-- Page level plugins -->
          <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
          <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
          <script src="js/demo/chart-area-demo.js"></script>  
          <!-- Page level custom scripts -->
          <script>
            $(document).ready(function () {
              $('#dataTable').DataTable(); // ID From dataTable 
              $('#dataTableHover').DataTable(); // ID From dataTable with Hover
            });
          </script>
</body>

</html>