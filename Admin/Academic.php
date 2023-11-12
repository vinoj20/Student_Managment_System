<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {

  $academicyear = $_POST['academicyear'];
  $semester = $_POST['semester'];
  $dateCreated = date("Y-m-d");

  $query1 = mysqli_query($conn, "select * from tblacademic") or die;
  $rowcount = mysqli_num_rows($query1);
  $id = $rowcount + 1;

  $query = mysqli_query($conn, "select * from tblacademic where sessionName ='$academicyear' and semesterId = '$semester'") or die;
  $ret = mysqli_fetch_array($query);

  if ($ret > 0) {
    $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>This Session Already Exists!</div>";
  } else {

    $query = mysqli_query($conn, "insert into tblacademic(id,sessionName,semesterId,isActive,dateCreated) value('$id','$academicyear','$semester','InActive','$dateCreated')");

    if ($query) {
      $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Created Successfully!</div>";
    } else {
      $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}

//---------------------------------------START-------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "start") {
  $id = $_GET['Id'];
  $date = date("Y-m-d");

  $q = mysqli_query($conn, "select IsActive from tblacademic where id='$id'") or die;
  $rows = $q->fetch_assoc();

  if ($rows['IsActive'] == 'Active' || $rows['IsActive'] == 'Completed') {
    echo "<script type = \"text/javascript\">
    window.location = (\"Academic.php\")
    </script>";
  } else {
    $que = mysqli_query($conn, "select * from tblacademic where IsActive='Active'") or die;
    $rowcount = mysqli_num_rows($que);
    if ($rowcount > 0) {
      echo "<script type = \"text/javascript\">
      window.location = (\"Academic.php\")
      </script>";
    } else {
      
        $q1 = mysqli_query($conn, "SELECT tbllecturer.id as Lid,tblsubject.id as Cid, tblsubject.NoofHours FROM tblsubject 
        JOIN tblacademic ON tblacademic.semesterId = tblsubject.semester 
        JOIN tbllectureenroll ON tblsubject.id = tbllectureenroll.Cid 
        join tbllecturer on tbllecturer.Id=tbllectureenroll.Lid
        WHERE tblacademic.semesterId =(SELECT tblacademic.semesterId from tblacademic where tblacademic.id=$id) 
        and tblsubject.isAssigned=1 GROUP by tblsubject.id") or die;
        $n = mysqli_num_rows($q1);

        if ($n > 0) {
          $sn = 0;
          while ($r = $q1->fetch_assoc()) {
            $query = mysqli_query($conn, "update tblacademic set StartDate='$date' where id='$id'") or die;
            $query = mysqli_query($conn, "update tblacademic set isActive='Active' where id='$id'") or die;
            $Cid = $r['Cid'];
            $Rh = $r['NoofHours'];
            $Lid = $r['Lid'];
            $sn = $sn + 1;
            $Q1 = mysqli_query($conn, "insert into tblhoursremain(Id,courseId,RemainHours,Lid) value('$sn','$Cid','$Rh','$Lid')") or die;
            
          }

          echo "<script type = \"text/javascript\">
              window.location = (\"Academic.php\")
              </script>";

        } else {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred! ( Check lecturer assigned for all courses for this semester )</div>";


        }



      } 


    }


  }








//--------------------Start------------------------------------------------------------




//--------------------------------COMPLETE------------------------------------------------------------------
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "complete") {

  $id = $_GET['Id'];
  $date = date("Y-m-d");


  $q = mysqli_query($conn, "select IsActive from tblacademic where id='$id'");
  $rows = $q->fetch_assoc();

  if ($rows['IsActive'] == 'Completed') {
    echo "<script type = \"text/javascript\">
    window.location = (\"Academic.php\")
    </script>";

  } else if ($rows['IsActive'] == 'InActive') {

    echo "<script type = \"text/javascript\">
    window.location = (\"Academic.php\")
    </script>";

  } else {
    $query = mysqli_query($conn, "update tblacademic set isActive='Completed' where id='$id'");
    if ($query == TRUE) {
      $query = mysqli_query($conn, "update tblacademic set EndDate='$date' where id='$id'");
      $q = mysqli_query($conn, "delete from tblhoursremain");
      echo "<script type = \"text/javascript\">
              window.location = (\"Academic.php\")
              </script>";
    } else {

      $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }


}



//--------------------------------COMPLETE------------------------------------------------------------------

//--------------------------------Reset-----------------------------------------------------------------
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "reset") {

  $id = $_GET['Id'];

  $query = mysqli_query($conn, "update tblacademic set isActive='InActive' where id='$id'");

  if ($query == TRUE) {
    $query = mysqli_query($conn, "update tblacademic set StartDate=' ',EndDate=' ' where id='$id'");
    $q = mysqli_query($conn, "delete from tblhoursremain");
    echo "<script type = \"text/javascript\">
        window.location = (\"Academic.php\")
        </script>";
  } else {

    $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
  }



}



//--------------------------------Reset------------------------------------------------------------------



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
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <!-- Page level plugins -->  
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
            <h1 class="h3 mb-0 text-gray-800">Manage Year And Semester</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Manage Academic</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Academic</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Academic Year<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="academicyear"
                          value="<?php echo $row['sessionName']; ?>" id="exampleInputFirstName" placeholder="Session">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Semester<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * FROM tblsemester ORDER BY Id ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;
                        if ($num > 0) {
                          echo ' <select required name="semester" class="form-control mb-3">';
                          echo '<option value="">--Select Semester--</option>';
                          while ($rows = $result->fetch_assoc()) {
                            echo '<option value="' . $rows['Id'] . '" >' . $rows['SemesterName'] . '</option>';
                          }
                          echo '</select>';
                        }
                        ?>
                      </div>
                    </div>

                    <button type="submit" name="save" class="btn btn-primary">Add</button>

                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Academic Year and Semester Data</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Start</th>
                            <th>Complete</th>
                            <th>Reset</th>
                          </tr>
                        </thead>

                        <tbody>

                          <?php
                          $query = "SELECT tblacademic.id,tblacademic.sessionName,tblacademic.isActive,tblacademic.dateCreated,tblacademic.StartDate,tblacademic.EndDate,
                      tblsemester.SemesterName
                      FROM tblacademic
                      INNER JOIN tblsemester ON tblsemester.Id = tblacademic.semesterId ORDER by id DESC";
                          $rs = $conn->query($query);
                          $num = $rs->num_rows;
                          $sn = 0;
                          if ($num > 0) {
                            while ($rows = $rs->fetch_assoc()) {

                              $sn = $sn + 1;
                              echo "
                              <tr>
                                <td>" . $sn . "</td>
                                <td>" . $rows['sessionName'] . "</td>
                                <td>" . $rows['SemesterName'] . "</td>
                                <td style='background-color:#dce9fa;font-size:20px;color:#c40e26;font-weight:bold'>" . $rows['isActive'] . "</td>
                                <td>" . $rows['StartDate'] . "</td>
                                <td>" . $rows['EndDate'] . "</td>
                                <td><a href='?action=start&Id=" . $rows['id'] . "'><i class='fas fa-fw fa-check'></i></a></td>
                                <td><a href='?action=complete&Id=" . $rows['id'] . "'><i class='fas fa-fw fa-check'></i></a></td>
                                <td><a href='?action=reset&Id=" . $rows['id'] . "'><i class='fas fa-fw fa-check'></i></a></td>
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
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>  
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../vendor/chart.js/Chart.min.js"></script>
    <script>
      $(document).ready(function () {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      });
    </script>
    
    
</body>

</html>