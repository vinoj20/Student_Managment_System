<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
$reg=$_SESSION['RegNumber'];
$N = $_GET['name'];
$Cid = $_GET['Cid'];

if (isset($Cid) && isset($N)) {
    $qu = mysqli_query($conn, "SELECT tbllecturer.firstName,tbllecturer.lastName from tbllecturer 
    join tbllectureenroll on tbllectureenroll.Lid=tbllecturer.Id where tbllectureenroll.Cid=$Cid;");
    $nl = mysqli_num_rows($qu);
    if ($nl > 0) {
        $row = $qu->fetch_assoc();
        $LN = $row['firstName'] . " " . $row['lastName'];
    } else {
        $LN = "Lecturer Not Assigned Still";
    }
    $Q = mysqli_query($conn, "select * from tblstudentenroll where Cid=$Cid and SRegNumber='$reg'");
    $n = mysqli_num_rows($Q);
    if ($n > 0) {
        $details = 1;
    } else {
        $enroll = 1;
    }
}

if (isset($_POST['enroll'])) {
    
    $qr = mysqli_query($conn, "select * from tblstudentenroll where Cid='$Cid' and SRegNumber='$reg' ");
    $num = mysqli_num_rows($qr); 
    if($num==0){
        $key = $_POST['Ekey'];
        $query = mysqli_query($conn, "select * from tbllectureenroll where Cid=$Cid");
        $num = mysqli_num_rows($query);
        if ($num > 0) {
            $r = $query->fetch_assoc();
            $ek = $r['EnrollKey'];

            if ($key == $ek) {
                $query2 = mysqli_query($conn, "INSERT into tblstudentenroll(Cid,SRegNumber) 
                value($Cid,'$reg')");
                if ($query2) {
                    echo "<script type = \"text/javascript\">
                        window.location = (\"coursedetails.php?Cid=$Cid&name=$N\")
                        </script>";
                } else {
                    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
                }

            } else {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Incorrect Enrollement key </div>";
            }
        } else {
            $statusMsg = "<div class='alert alert-danger'  style='margin-right:700px;'>Lecture not Assigned Still Try Again later</div>";

        }

    }
    
}

if (isset($_POST['disenroll'])) {
    $query = mysqli_query($conn, "DELETE FROM tblstudentenroll WHERE SRegNumber='$reg' and Cid=$Cid");
    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">
        window.location = (\"coursedetails.php?Cid=$Cid&name=$N\")
        </script>";
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
                <!-- Enrollment -->
                <?php if (isset($enroll)) { ?>
                    <div class="container-fluid" id="container-wrapper">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800" style="font-size:30px;text-color:#04063d">
                                Course Manage
                            </h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">enroll</li>
                            </ol>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Enrollment for
                                            <?php echo $N ?>
                                        </h6>
                                        <?php echo $statusMsg; ?>
                                    </div>
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="form-group row mb-3">
                                                <div class="col-xl-6">
                                                    <label class="form-control-label">Enter enrollment key<span
                                                            class="text-danger ml-2">*</span></label>
                                                    <input type="text" class="form-control" required name="Ekey"
                                                        id="exampleInputFirstName">
                                                </div>
                                                <!-- <div class="col-xl-6">
                        
                        </div> -->
                                            </div>
                                            <button type="submit" name="enroll" class="btn btn-primary">Enroll</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- Enrollment -->

                <!-- course -->
                <?php if (isset($details)) { ?>
                    <div class="container-fluid" id="container-wrapper">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">
                                Course Manage
                            </h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">courses</li>
                            </ol>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary" style="font-size:30px;">
                                            <?php echo $N ?>
                                        </h6>
                                        <h6 style="color:#f21707;font-size:20px;font-weight:bold">
                                           Lecturer : <?php echo $LN ?>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                    <h6 style="color:black;font-size:20px;font-weight:bold">
                                    currently No content Available for this Course
                                    </h6>
                                    
                                    
                                    </div>
                                    <div class="card-body">
                                    <form method="post">
                                        <br></br>
                                    <button type="submit" name="disenroll" class="btn btn-primary" style="background-color:#f21707">DisEnroll</button>
                                    </form>
                                    
                                    
                                    </div>
                                    
                                </div>

                            </div>
                            <!---Container Fluid-->
                        </div>
                    </div>
                <?php } ?>
                <!-- course -->
                <!-- Footer -->
                <?php include "Includes/footer.php"; ?>
                <!-- Footer -->

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