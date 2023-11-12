<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {
  $Title=$_POST['Title'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $emailAddress = $_POST['emailAddress'];
  $phoneNo = $_POST['phoneNo'];
  $dateCreated = date("Y-m-d");
  $nic = $_POST['nic'];
  $sampPass = "FASL$nic";
  $sampPass_2 = password_hash($sampPass, PASSWORD_DEFAULT); // Securely hash the password
  $query = mysqli_query($conn, "select * from tbllecturer where emailAddress ='$emailAddress' or Nicno='$nic'");
  $ret = mysqli_fetch_array($query);
  if ($ret > 0) {
    $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>This Email Address Or Nic No Already Exists!</div>";
  } else {

    $query = mysqli_query($conn, "select * from tbllecturer");
    $rowcount = mysqli_num_rows($query);
    $id = $rowcount + 1;

    $query1 = mysqli_query($conn, "INSERT into tbllecturer(Id,Title,firstName,lastName,emailAddress,username,password,phoneNo,dateCreated,Nicno) 
    value('$id','$Title','$firstName','$lastName','$emailAddress','$emailAddress','$sampPass_2','$phoneNo','$dateCreated','$nic')");

    if ($query1) {
      $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Created Successfully!</div>";

    } else {
      $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}


//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['confirm']) && $_GET['confirm'] == 'yes' && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['Id'];
  $query = mysqli_query($conn, "DELETE FROM tbllecturer WHERE Id='$Id'");

  if ($query == TRUE) {
    $query = "SELECT * from tbllectureenroll WHERE Lid ='$Id'";
    $rs = $conn->query($query);
    $num = $rs->num_rows;

    while ($row = $rs->fetch_assoc()) {
      $Cid = $row['Cid'];
      // Update the tblsubject for the current Cid
      $qu = mysqli_query($conn, "UPDATE tblsubject SET isAssigned='0' WHERE id='$Cid'") or die;
    }

    $qu1 = mysqli_query($conn, "DELETE FROM tbllectureenroll WHERE Lid='$Id'") or die;
    echo "<script type = \"text/javascript\">
    window.location = (\"Lecturer.php\")
    </script>";
  } else {

    $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
  }

}

//--------------------------------Edit------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];

  $query = mysqli_query($conn, "select * from tbllecturer where Id ='$Id'");
  $row = mysqli_fetch_array($query);

  //------------UPDATE-----------------------------

  if (isset($_POST['update'])) {
    $Title=$_POST['Title'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $phoneNo = $_POST['phoneNo'];
    $nic = $_POST['nic'];
    
    $query = mysqli_query($conn, "select * from tbllecturer where emailAddress ='$emailAddress' or Nicno='$nic'and NOT Id='$Id'");
    $ret = mysqli_fetch_array($query);
    if ($ret > 0) {
  
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Email Address Or Nic No Already Exists!</div>";
    } else {
      $query = mysqli_query($conn, "update tbllecturer set Title='$Title',firstName='$firstName', lastName='$lastName',
                phoneNo='$phoneNo',emailAddress='$emailAddress',Nicno='$nic'
                where Id='$Id'");
      if ($query) {
        $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Updated Successfully!</div>";
      } else {
        $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
      }
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
            <h1 class="h3 mb-0 text-gray-800">Create Lectures</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Lectures</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Lectures</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                    <div class="col-xl-2">
                      <label class="form-control-label">Title<span class="text-danger ml-2">*</span></label>
                      <select required name="Title" class="form-control mb-3" value="<?php echo $row['Title']; ?>">
                              <option value="Mr" <?php if ($row['Title'] === 'Mr') echo 'selected'; ?>>Mr.</option>
                              <option value="Miss" <?php if ($row['Title'] === 'Miss') echo 'selected'; ?>>Miss.</option>
                              <option value="Mrs" <?php if ($row['Title'] === 'Mrs') echo 'selected'; ?>>Mrs.</option>
                              <option value="Dr" <?php if ($row['Title'] === 'Dr') echo 'selected'; ?>>Dr.</option>
                              <option value="Prof" <?php if ($row['Title'] === 'Prof') echo 'selected'; ?>>Prof.</option>
                      </select>
                      </div>
                      <div class="col-xl-5">
                        <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="firstName"
                          value="<?php echo $row['firstName']; ?>" id="exampleInputFirstName">
                      </div>
                      <div class="col-xl-5">
                        <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="lastName"
                          value="<?php echo $row['lastName']; ?>" id="exampleInputFirstName">
                      </div>
                    </div>

                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                        <input type="email" class="form-control" required name="emailAddress"
                          value="<?php echo $row['emailAddress']; ?>" id="exampleInputFirstName">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Phone No<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="phoneNo"
                          value="<?php echo $row['phoneNo']; ?>" id="exampleInputFirstName">
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Nic No<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="nic" value="<?php echo $row['Nicno']; ?>"
                          id="exampleInputFirstName">
                      </div>
                    </div>
                    <?php
                    if (isset($Id)) {
                      ?>
                      <button type="submit" name="update" class="btn btn-warning">Update</button>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <?php
                    } else {
                      ?>
                      <button type="submit" name="save" class="btn btn-primary">Save</button>
                      <?php
                    }
                    ?>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Lecturers</h6>
                      <a href='' onclick="return confirmDeleteAll('lecturer')"><h6 style="color:Red;font-weight: bold;">Delete All</h6><i class='fas fa-fw fa-trash' style="color: red;"></i></a>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Phone No</th>
                            <th>Date Created</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>

                        <tbody>

                          <?php
                          $query = "SELECT * FROM tbllecturer";

                          $rs = $conn->query($query);
                          $num = $rs->num_rows;
                          $sn = 0;
                          $status = "";
                          if ($num > 0) {
                            while ($rows = $rs->fetch_assoc()) {
                              $name = $rows['Title'] . ' ' . $rows['firstName'] . ' ' . $rows['lastName'];
                              $sn = $sn + 1;
                              echo "
                              <tr>
                                <td>" . $sn . "</td>
                                <td>" . $name. "</td>
                                <td>" . $rows['emailAddress'] . "</td>
                                <td>" . $rows['phoneNo'] . "</td>
                                <td>" . $rows['dateCreated'] . "</td>
                                <td><a href='?action=edit&Id=" . $rows['Id'] . "'><i class='fas fa-fw fa-edit'></i></a></td>
                                <td><a href=''  onclick='return confirmDelete(".$rows['Id'].")'><i class='fas fa-fw fa-trash'></i></a></td>
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

  <script src="js/demo/chart-area-demo.js"></script>  
  <script>
      $(document).ready(function () {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      });
    </script>
</body>

</html>