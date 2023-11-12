<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
$reg = $_SESSION['RegNumber'];


if (isset($_POST['view'])) {
  $check = 1;
  $C = $_POST['id'];
  $Q = "Select code,Name from tblsubject where id=$C";
  $result = $conn->query($Q);
  $num = $result->num_rows;
  if ($num > 0) {
    while ($rows = $result->fetch_assoc()) {
      $N = $rows['code'] . " " . $rows['Name'];
    }
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
            <h1 class="h3 mb-0 text-gray-800">View Student Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Student Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Student Attendance</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Course<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry ="SELECT tblsubject.id,tblsubject.code,tblsubject.Name from tblsubject 
                        join tblstudentenroll on tblstudentenroll.Cid=tblsubject.id
                        WHERE tblstudentenroll.SRegNumber='$reg'";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;
                        if ($num > 0) {
                          echo ' <select required name="id" onchange="typeDropDown(this.value)" class="form-control mb-3">';
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

                    <!-- <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Select Student<span class="text-danger ml-2">*</span></label>
                        
                        </div>
                        <div class="col-xl-6">
                        <label class="form-control-label">Type<span class="text-danger ml-2">*</span></label>
                        
                        </div>
                    </div> -->
                    <button type="submit" name="view" class="btn btn-primary">View Attendance</button>
                  </form>
                </div>
              </div>
             
                <!-- Input Group -->
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card mb-4">
                      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Students Attendance for
                          <?php echo $N ?>
                        </h6>
                      </div>
                      <div class="table-responsive p-3">
                        <table class="table align-items-center table-flush table-hover" id="dataTableHover" style="text-align: center;">
                          <thead class="thead-light" >
                            <tr>
                              <th>#</th>
                              <th>Course Name</th>
                              <th>Attendance percentage</th>
                            </tr>
                          </thead>

                          <tbody>

                            <?php
                      
                            if (isset($_POST['view'])) {
                              $CId = $_POST['id'];
                              $type = $_POST['student'];
                              $q = "SELECT tblsubject.NoofHours, tblhoursremain.RemainHours,
                               COUNT(tblattendance.status) AS attend FROM tblsubject
                               JOIN tblhoursremain ON tblhoursremain.courseId = tblsubject.id 
                               JOIN tblattendance ON tblattendance.Cid = tblsubject.id AND tblattendance.Cid =$CId 
                               JOIN tblstudents ON tblattendance.RegNo = tblstudents.RegNumber WHERE tblattendance.status = 1 
                               GROUP BY tblstudents.RegNumber";
                              $rs = $conn->query($q);
                              $num = $rs->num_rows;
                              $sn = 0;

                              if ($q && $num > 0) {
                                  $r = $rs->fetch_assoc();
                                  $Name = $r['firstName'] . " " . $r['lastName'];
                                  $RN = $r['RegNumber'];
                                  
                                  $attend=$r['attend'];
                                  $lh=$r['NoofHours']-$r['RemainHours'];
                                  if($lh==0){
                                    $percentage='Still Not Start';
                                  }else{
                                    $percentage=($attend/$lh)*100 ."%";

                                  }
                                  
                                  if ($percentage<100) {
                                    $colour = "#f71a0a";
                                  } else {
                                    $colour = "#0af765";
                                  }
                                  $sn = $sn + 1;
                                  echo "
                                <tr>
                                  <td>" . $sn . "</td>
                                  <td>" . $N . "</td>
                                  <td style='color:" . $colour . ";font-size:25px'><b>" . $percentage ."</b></td>
                                  
                                </tr>";
                                
                              } else {
                                echo
                                  "<div class='alert alert-danger' role='alert'>
                              No Record Found!
                              </div>";
                              }

                            }

                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              
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