<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
$Lid = $_SESSION['userId'];

$query = "SELECT tblacademic.sessionName,tblsemester.SemesterName from tblacademic 
join tblsemester on tblacademic.semesterId=tblsemester.Id where tblacademic.isActive='Active'";
$rs = $conn->query($query);
$num = $rs->num_rows;
$rows = $rs->fetch_assoc();
$year;
$semester;
if ($num == 0) {
  $year;
  $semester;
} else {
  $year = $rows['sessionName'];
  $semester = $rows['SemesterName'];
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
  </script>


  <script>
    function classArmDropdown(str) {
      if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
      } else {
        if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();
        } else {
          // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET", "ajaxClassArms2.php?cid=" + str, true);
        xmlhttp.send();
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
            <h1 class="h3 mb-0 text-gray-800">Get Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Get Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->


              <!-- Input Group -->
              <form method="post">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card mb-4">
                      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <div class="col-xl-6">
                          <label class="form-control-label">Select Course<span class="text-danger ml-2">*</span></label>
                          <?php
                          $qry = "SELECT tblsubject.code,tblsubject.Name from tblsubject join tbllectureenroll on tbllectureenroll.Cid=tblsubject.id WHERE tbllectureenroll.Lid=$Lid";
                          $result = $conn->query($qry);
                          $num = $result->num_rows;
                          if ($num > 0) {

                            echo ' <select required name="id" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                            echo '<option value="non">--Select Course--</option>';
                            while ($rows = $result->fetch_assoc()) {
                              $CourseN = $rows['code'] . " " . $rows['Name'];

                              echo '<option value="' . $rows['id'] . '" >' . $CourseN . '</option>';

                            }
                            echo '</select>';
                          } else {
                            echo ' <select required name="" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                            echo '<option value="">--Select Course--</option>';
                            echo '<option>No Course Availables</option>';
                            echo '</select>';
                          }
                          ?>
                        </div>
                      </div>

                      <div class="table-responsive p-3">
                        <button href="#" type="submit" name="save" class="btn btn-primary">Take Attendance</button>
              </form>



            </div>

          </div>
        </div>

        <!--Row-->


        <div class="row">
          <div class="col-lg-12 text-center">

          </div>

        </div>
      </div>
    </div>
  </div>

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

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>