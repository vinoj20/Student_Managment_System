<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {
  
  $lecture = $_POST['lecture'];
  $course = $_POST['course'];

  $q=mysqli_query($conn,"Select * from tblsubject where id='$course'");
  $row = $q->fetch_assoc();
  $code=$row['code'];
  
  $query = mysqli_query($conn, "INSERT INTO tbllectureenroll (Cid, Lid, EnrollKey) VALUES ('$course', '$lecture', '$code')");
  $query1 = mysqli_query($conn, "update tblsubject set isAssigned='1' where id='$course'");
  $qu=mysqli_query($conn,"UPDATE tblhoursremain SET Lid =$lecture WHERE courseId=$course");
  if ($query) {
    $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Assigned Successfully!</div>";
  } else {
    $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
  }

}

//---------------------------------------EDIT-------------------------------------------------------------






//--------------------EDIT------------------------------------------------------------

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
  $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>click</div>";
  $id = $_GET['Id'];
  $query = mysqli_query($conn, "update tblsubject set isAssigned='0' where id='$id'");

  if ($query == TRUE) {
    $query = mysqli_query($conn, "Delete from tbllectureenroll where Cid='$id'");
    $qu=mysqli_query($conn,"UPDATE tblhoursremain SET `Lid` = '' WHERE courseId='$id'");
    if($query and $qu ){
      $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Removed succesfully</div>";
    }else{
      $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
    }
  } else {

    $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
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
  <?php include 'includes/title.php'; ?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <link href="css/ruang-admin.css" rel="stylesheet">

  <script src="js/ruang-admin.js"></script>
  <!-- Page level plugins -->

  <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    setTimeout(removeMessage, 2000);
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
            <h1 class="h3 mb-0 text-gray-800">Lecture Enrollment</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Assign Lecture</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Assign Lecture for the course</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Lecture<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * FROM tbllecturer ORDER BY Id ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;
                        if ($num > 0) {
                          echo ' <select required name="lecture" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                          echo '<option value="">--Select Lecture--</option>';
                          while ($rows = $result->fetch_assoc()) {
                            echo '<option value="' . $rows['Id'] . '" >' . $rows['firstName'] . " " . $rows['lastName'] . '</option>';
                          }
                          echo '</select>';
                        } else {
                          echo ' <select required name="" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                          echo '<option value="">--Select Lecture--</option>';
                          echo '<option>No Lecture Available</option>';
                          echo '</select>';
                        }
                        ?>
                      </div>

                      <div class="col-xl-6">
                        <label class="form-control-label">Select Course<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * FROM tblsubject where isAssigned='0' ORDER BY id ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;
                        if ($num > 0) {
                          echo ' <select required name="course" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                          echo '<option value="">--Select Course--</option>';
                          while ($rows = $result->fetch_assoc()) {
                            echo '<option value="' . $rows['id'] . '" >' . $rows['code'] . " " . $rows['Name'] . '</option>';
                          }
                          echo '</select>';
                        } else {
                          echo ' <select required name="classId" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                          echo '<option value="">--Select Course--</option>';
                          echo '<option>No Course Available</option>';
                          echo '</select>';
                        }
                        ?>
                        <button type="submit" name="save" class="btn btn-primary">Assign</button>

                      </div>
                    </div>
                    
                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Assigned Lecturers</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Lecturer Name</th>
                            <th>Course Name</th>
                            <th>Remove</th>
                          </tr>
                        </thead>

                        <tbody>

                          <?php
                          $query = "SELECT tbllecturer.firstName,tbllecturer.lastName,tblsubject.code,tblsubject.id,tblsubject.Name from tbllecturer
                      JOIN tbllectureenroll ON tbllecturer.Id=tbllectureenroll.Lid 
                      JOIN tblsubject ON tblsubject.id = tbllectureenroll.Cid";
                          $rs = $conn->query($query);
                          $num = $rs->num_rows;
                          $sn = 0;
                          $status = "";
                          if ($num > 0) {
                            while ($rows = $rs->fetch_assoc()) {
                              $sn = $sn + 1;
                              echo "
                              <tr>
                                <td>" . $sn . "</td>
                                <td>" . $rows['firstName'] . " " . $rows['lastName'] . "</td>
                                <td>" . $rows['code'] . "  " . $rows['Name'] . "</td>  
                                <td><a href='' onclick='return confirmDelete(".$rows['id'].")'><i class='fas fa-fw fa-trash'></i></a></td>
                              </tr>";
                            }
                          } else {
                            echo
                              "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
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
    <!-- Page level custom scripts -->
    <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>  
    <script>
      $(document).ready(function () {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      });
    </script>
</body>

</html>