<?php
error_reporting(1);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Initialize status message to an empty string
$statusMsg = "";
$label_remove=1;
//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {
  $Title = $_POST['Title'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $NicNo = $_POST['nic'];
  $dateCreated = date("Y-m-d");
  $email = $_POST['emailAddress'];
  $academic = $_POST['Academic'];
  $sampPass = $NicNo;
  $sampPass_2 = password_hash($sampPass, PASSWORD_DEFAULT); // Securely hash the password
  $name = $_FILES['file']['name'];
  $file = $_FILES['file']['tmp_name'];
  $allowedImageTypes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);

  $Extension = pathinfo($name, PATHINFO_EXTENSION);

  $query = mysqli_query($conn, "SELECT * FROM tblstudents WHERE Nicno ='$NicNo'");
  $ret = mysqli_fetch_array($query);

  if ($ret > 0) {
      $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>This Nic No Already Exists!</div>";
  } else {
      $query = mysqli_query($conn, "SELECT * FROM tblstudents WHERE Academic_year='$academic'");
      $rowcount = mysqli_num_rows($query);
      $id = $rowcount + 1;

      $id4 = sprintf('%04d', $id);
      $ac = substr($academic, 2, 2);
      $ac = (int)$ac;
      $regno = "SEU" . $ac . "FAS" . $id4;

      if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
          // File was uploaded successfully
          $imageInfo = getimagesize($file);
          $filename = $regno . '.' . $Extension;
      }
      // Use prepared statements to prevent SQL injection
      $stmt = $conn->prepare("INSERT INTO tblstudents(id, Title, firstName, lastName, Academic_year, RegNumber, Nicno, email, username, password, dateCreated, CourseEnrollStatus,status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("issssssssssss", $id, $Title, $firstName, $lastName, $academic, $regno, $NicNo, $email, $regno, $sampPass_2, $dateCreated, $courseEnrollStatus, $status);

      // Define the initial values for some variables...
      $courseEnrollStatus = '0';
      $status = 'Active';

      if ($query = $stmt->execute()) {
          if ($imageInfo && in_array($imageInfo[2], $allowedImageTypes) || empty($_FILES['file']['name'])) {
              if (move_uploaded_file($file, 'Files/students/' . $filename)) {
                  $query1 = mysqli_query($conn, "INSERT INTO tblprofile(Student, Name) VALUES ('$regno', '$filename')");
                  $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Created Successfully!</div>";
              } else {
                  $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Created Successfully!, But Profile Upload failed</div>";
              }
          } else {
              $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Created Successfully!, But Profile Upload failed (File type Not Supported)</div>";
          }
      } else {
          $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
      }
  }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    $regno = $_GET['Id'];
    $query = mysqli_query($conn, "UPDATE tblstudents SET status='Removed' WHERE RegNumber='$regno'");
    if ($query == TRUE) {
        $que = mysqli_query($conn, "DELETE FROM tblstudentenroll WHERE SRegNumber='$regno'");
        $que = mysqli_query($conn, "DELETE FROM tblprofile WHERE Student='$regno'");
        $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Deleted Successfully!</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
    }
}

//--------------------------------Edit------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $label_remove = 0;
    $regno = $_GET['Id'];
    $query = mysqli_query($conn, "SELECT * FROM tblstudents WHERE RegNumber ='$regno'");
    $row = mysqli_fetch_array($query);

    //------------UPDATE-----------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $Title = $_POST['Title'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $emailAddress = $_POST['emailAddress'];
        $nic = $_POST['nic'];
        $file = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $allowedImageTypes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);
        $Extension = pathinfo($name, PATHINFO_EXTENSION);

        $query = mysqli_query($conn, "SELECT * FROM tblstudents WHERE Nicno ='$nic' AND RegNumber != '$regno'");
        $ret = mysqli_fetch_array($query);
        if ($ret > 0) {
            $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>This Nic No Already Exists!</div>";
        } else {
            $query = mysqli_query($conn, "UPDATE tblstudents SET Title='$Title',firstName='$firstName', lastName='$lastName', email='$emailAddress',Nicno='$nic' WHERE RegNumber='$regno'");
            
            //profile update
            if ($query == TRUE) {
                //set name 
                if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    // File was uploaded successfully
                    $imageInfo = getimagesize($file);
                    $filename = $regno . '.' . $Extension;
                } else {
                    // No file was uploaded or an error occurred during upload
                    $filename = "unknown.jpg";
                }
                //update profile
                if ($imageInfo && in_array($imageInfo[2], $allowedImageTypes) || empty($_FILES['file']['name'])) {
                    if (move_uploaded_file($file, 'Files/students/' . $filename)) {
                        $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>updated Successfully!</div>";
                       
                        //check student already have profile photo
                        $qr = mysqli_query($conn, "SELECT * FROM tblprofile WHERE student = '$regno'");
                        $re = mysqli_fetch_array($qr);
                        if($re>0){
                            //update
                            $query1 = mysqli_query($conn, "update tblprofile set Name='$filename' where student='$regno'");
                        }else{
                            //insert
                            $query1 = mysqli_query($conn, "INSERT INTO tblprofile (student, Name) VALUES ('$regno', '$filename')");
                        }
                    } else {
                        $statusMsg = "<div class='alert alert-success' id='ms' style='margin-right:700px;'>updated Successfully!, But Profile Upload failed</div>";
                    }

                } else {
                    $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>updated Successfully!, But Profile Upload failed (File type Not Supported)</div>";
                }
            }else {
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
    <link rel="stylesheet" type="text/css" href="css/sweetalert.css">
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
                        <h1 class="h3 mb-0 text-gray-800">Create Students</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Students</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Basic -->
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Create Students</h6>
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype='multipart/form-data'>
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
                                                <input type="text" class="form-control" required name="firstName" value="<?php echo $row['firstName']; ?>" id="exampleInputFirstName">
                                            </div>
                                            <div class="col-xl-5">
                                                <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" required name="lastName" value="<?php echo $row['lastName']; ?>" id="exampleInputFirstName">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <?php if ($label_remove > 0) { ?>
                                                <div class="col-xl-2">
                                                    <label class="form-control-label">Select Academic year<span class="text-danger ml-2">*</span></label>
                                                    <?php
                                                    $qry = "SELECT DISTINCT sessionName FROM tblacademic ORDER BY Id ASC";
                                                    $result = $conn->query($qry);
                                                    $num = $result->num_rows;
                                                    if ($num > 0) {
                                                        echo ' <select required name="Academic" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                                                        echo '<option value="">--Select Academic year--</option>';
                                                        while ($rows = $result->fetch_assoc()) {
                                                            echo '<option value="' . $rows['sessionName'] . '">' . $rows['sessionName'] . '</option>';
                                                        }
                                                        echo '</select>';
                                                    } else {
                                                        echo ' <select required name="" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                                                        echo '<option value="">--Select Academic year--</option>';
                                                        echo '<option>No Academic year Available</option>';
                                                        echo '</select>';
                                                    }
                                                    ?>
                                                </div>
                                            <?php } ?>
                                            <div class="col-xl-5">
                                                <label class="form-control-label">NIC Number<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" required name="nic" value="<?php echo $row['Nicno']; ?>" id="exampleInputFirstName">
                                            </div>
                                            <div class="col-xl-5">
                                                <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                                                <input type="email" class="form-control" required name="emailAddress" value="<?php echo $row['email']; ?>" id="exampleInputFirstName">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Profile Picture</label>
                                                <input name='file' type='file' id='file'>
                                            </div>
                                        </div>

                                        <?php
                                          if ($_GET['action'] == "edit") {
                                            ?>
                                            <button type="submit" name="update" class="btn btn-warning">Update</button>
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
                                            <h6 class="m-0 font-weight-bold text-primary">All Student</h6>
                                            <a href='' onclick="return confirmDeleteAll('student')">
                                                <h6 style="color:Red;font-weight: bold;">Delete All</h6><i class='fas fa-fw fa-trash' style="color: red;"></i>
                                            </a>
                                        </div>
                                        <div class="table-responsive p-3">

                                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Academic Year</th>
                                                        <th>Registration No</th>
                                                        <th>Email</th>
                                                        <th>Enrolled Courses</th>
                                                        <th>Print Id</th>
                                                        <th>Edit</th>
                                                        <th>Remove</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query = "SELECT * from tblstudents where status='Active'";
                                                    $rs = $conn->query($query);
                                                    $num = $rs->num_rows;

                                                    $sn = 0;
                                                    $status = "";

                                                    if ($num > 0) {
                                                        while ($row = $rs->fetch_assoc()) {
                                                            $reg = $row['RegNumber'];
                                                            $query = mysqli_query($conn, "SELECT * from tblstudentenroll where SRegnumber='$reg'");
                                                            $count = mysqli_num_rows($query);
                                                            $regNumber = $row['RegNumber'];
                                                            $name = $row['Title'] . ' ' . $row['firstName'] . ' ' . $row['lastName'];
                                                            $sn = $sn + 1;
                                                            $academicYear = $row['Academic_year'];
                                                            echo "
                                                            <tr>
                                                                <td>" . $sn . "</td>
                                                                <td>" . $name . "</td>
                                                                <td>" . $academicYear. "</td>
                                                                <td>" . $regNumber . "</td>
                                                                <td>" . $row['email'] . "</td>
                                                                <td>" . $count . "</td>
                                                                <td><a href='print.php?home=1&academic=".$academicYear."&student=".$regNumber."' target='_blank'><i class='fas fa-fw fa-id-badge'></i></a></td>
                                                                <td><a href='?action=edit&Id=" . $row['RegNumber'] . "'><i class='fas fa-fw fa-edit'></i></a></td>
                                                                <td><a href='' onclick='return confirmDelete(\"$regNumber\")'><i class='fas fa-fw fa-trash'></i></a></td>
                                                            </tr>";
                                                        }
                                                    } else {
                                                        echo "<div class='alert alert-danger' role='alert'>
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
		<script src="../vendor/chart.js/Chart.min.js"></script>
        <script>
      $(document).ready(function () {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      });
    </script>
    </div>
</body>

</html>
