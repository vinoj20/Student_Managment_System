<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {

  $code = $_POST['code'];
  $Name = $_POST['name'];
  $type = $_POST['type'];
  $subjectname = $_POST['subject'];
  $year = $_POST['year'];
  $semester = $_POST['semester'];
  $nh=$_POST['nh'];
  $dateCreated = date("Y-m-d");
  
  $query = mysqli_query($conn, "select * from tblsubject where code ='$code'");
  $ret = mysqli_fetch_array($query);
  if ($ret > 0) {

    $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>This Course Already Exists!</div>";
  } else {

    $query = mysqli_query($conn, "select * from tblsubject");
    $rowcount = mysqli_num_rows($query);
    $id = $rowcount + 1;

    $query1 = mysqli_query($conn, "INSERT into tblsubject(id,code,Name,Type,SubjectName,year,semester,NoofHours,isAssigned,DateAdded) 
    value('$id','$code','$Name','$type','$subjectname','$year','$semester','$nh','0','$dateCreated')");

    if ($query1) {
      $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Added Successfully!</div>";

    } else {
      $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}

//--------------------------------Edit------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];
  $query = mysqli_query($conn, "select * from tblsubject where id ='$Id'");
  $row = mysqli_fetch_array($query);

  //------------UPDATE-----------------------------

  if (isset($_POST['update'])) {

    $code = $_POST['code'];
    $Name = $_POST['name'];
    $type = $_POST['type'];
    $subjectname = $_POST['subject'];
    $year = $_POST['year'];
    $semester = $_POST['semester'];
    $nh=$_POST['nh'];

    $query = mysqli_query($conn, "update tblsubject set code='$code', Name='$Name',
              Type='$type',SubjectName='$subjectname',Year='$year',semester='$semester',NoofHours='$nh'
              where id='$Id'");
    if ($query) {
      $statusMsg = "<div class='alert alert-success'  id='msg' style='margin-right:700px;'>Updated Successfully!</div>";
      
    } else {
      $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}

//--------------------------------Delete------------------------------------------------------------------
if (isset($_GET['Id']) && isset($_GET['confirm']) && $_GET['confirm'] == 'yes' && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Cid = $_GET['Id'];
  $query = mysqli_query($conn, "DELETE FROM tblsubject WHERE id='$Cid'");
  if ($query == TRUE) {
    $que=mysqli_query($conn,"DELETE FROM tblstudentenroll WHERE Cid='$Cid'");
    $que=mysqli_query($conn,"DELETE FROM tbllectureenroll WHERE Cid='$Cid'");
    $que=mysqli_query($conn,"DELETE FROM tblhoursremain WHERE courseId='$Cid'");
    $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Deleted Successfully!</div>";
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


  <!-- Page level custom scripts -->
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
            <h1 class="h3 mb-0 text-gray-800">Manage courses</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Manage courses</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create courses</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Course Code<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="code" value="<?php echo $row['code']; ?>"
                          id="exampleInputFirstName">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Course Name <span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="name" value="<?php echo $row['Name']; ?>"
                          id="exampleInputFirstName">
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Type<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * FROM tblcoursetype ORDER BY id ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;

                        echo '<select required name="type" onchange="classArmDropdown(this.value)" class="form-control mb-3" 
                        value='.$row['Name'].'>';
                        echo '<option value="">--Select Course Type-</option>';
                        while ($rows = $result->fetch_assoc()) {
                          echo '<option value="' . $rows['Type'] . '" >' . $rows['Type'] . '</option>';
                        }
                        echo '</select>';

                        ?>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Subject<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * FROM tblsubjectname ORDER BY Id ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;

                        echo '<select required name="subject" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                        echo '<option value="">--Select Subject Type-</option>';
                        while ($rows = $result->fetch_assoc()) {
                          echo '<option value="' . $rows['Name'] . '" >' . $rows['Name'] . '</option>';
                        }
                        echo '</select>';
                        ?>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Year<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * FROM tblyear ORDER BY Id ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;

                        echo '<select required name="year" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                        echo '<option value="">--Select Year-</option>';
                        while ($rows = $result->fetch_assoc()) {
                          echo '<option value="' . $rows['Id'] . '" >' . $rows['Year'] . '</option>';
                        }
                        echo '</select>';
                        ?>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Semester<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * FROM tblsemester ORDER BY Id ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;

                        echo '<select required name="semester" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                        echo '<option value="">--Select Semester-</option>';
                        while ($rows = $result->fetch_assoc()) {
                          echo '<option value="' . $rows['Id'] . '" >' . $rows['SemesterName'] . '</option>';
                        }
                        echo '</select>';
                        ?>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">No Of hours<span class="text-danger ml-2">*</span></label>
                        <input type="number" class="form-control" required name="nh" value="<?php echo $row['NoofHours']; ?>"
                          id="exampleInputFirstName">
                      </div>
                    </div>
                    <?php
                    if (isset($Id)) {
                      ?>
                      <button type="submit" name="update" class="btn btn-warning" onclick='return Redirect(5000)'>Update</button>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <?php
                    } else {
                      ?>
                      <button type="submit" name="save" class="btn btn-primary" onclick='return Redirect(5000)'>Save</button>
                      <?php
                    }
                    ?>
                </div>
                </form>
              </div>
            </div>

            <!-- Input Group -->
            <div class="row" style="width:100%">
              <div class="col-lg-12">
                <div class="card mb-4">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">All courses</h6>
                    <a href='' onclick="return confirmDeleteAll('course')"><h6 style="color:Red;font-weight: bold;">Delete All</h6><i class='fas fa-fw fa-trash' style="color: red;"></i></a>
                  </div>
                  <div class="table-responsive p-3">
                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Code</th>
                          <th>Name</th>
                          <th>Subject</th>
                          <th>Type</th>
                          <th>Year</th>
                          <th>Semester</th>
                          <th>IsAssigned</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>

                      <tbody>

                        <?php
                        $query = "SELECT * from tblsubject";
                        $rs = $conn->query($query);
                        $num = $rs->num_rows;
                        $sn = 0;
                        $status = "";
                        if ($num > 0) {
                          while ($rows = $rs->fetch_assoc()) {
                            if($rows['isAssigned']==1){
                              $status="Assigned";
                              $colour="#0af765";
                            }else{
                              $status="Not Yet";
                              $colour="#f71a0a";

                            }
                            $sn = $sn + 1;
                            echo "
                              <tr>
                                <td>" . $sn . "</td>
                                <td>" . $rows['code'] . "</td>
                                <td>" . $rows['Name'] . "</td>
                                <td>" . $rows['SubjectName'] . "</td>
                                <td>" . $rows['Type'] . "</td>
                                <td>" . $rows['year'] . "</td>
                                <td>" . $rows['semester'] . "</td>
                                <td style='color:" . $colour . ";text-align:center;font-size:25px'><b>" . $status . "</b></td>
                                <td><a href='?action=edit&Id=" . $rows['id'] . "'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
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
  <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="js/ruang-admin.min.js"></script>
        <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="js/sweetalert.min.js"></script>
        <script src="js/demo/chart-area-demo.js"></script> 
        <script>
      $(document).ready(function () {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      });
    </script> 
</body>

</html>