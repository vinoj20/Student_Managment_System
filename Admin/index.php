<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$query = "SELECT tblacademic.sessionName,tblsemester.SemesterName from tblacademic 
          join tblsemester on tblacademic.semesterId=tblsemester.Id where tblacademic.isActive='Active'";
          $rs = $conn->query($query);
          $num = $rs->num_rows;
          $rows = $rs->fetch_assoc();
          $msg1="";
          $msg2="";
          if($num==0){
            $msg1="Not yet";
            $msg2="Not yet";
          }else{
            $msg1=$rows['sessionName'];
            $msg2=$rows['SemesterName'];
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
</head>
<body id="page-top" >
  <div id="wrapper"  >
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        
        <!-- Topbar -->
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper" >
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Administrator Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

        <div class="row mb-3" >
          <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body" style="background-color:#9fc1f5 ;color:#080808;border-radius:10px">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1" style="font-size:14px">Active Academic Year : <b style="background-color:#98a9eb;color:#eb0911;font-size:15px"><?php echo $msg1?></b></div>
                      <div class="text-xs font-weight-bold text-uppercase mb-1" style="font-size:14px">Active Semester :<b style="background-color:#98a9eb;color:#eb0911;font-size:15px"><?php echo $msg2?></b></div>
                    </div>  
                  </div>
                </div>
              </div>
          </div>
            <!-- Students Card -->
            <?php
            $query1 = mysqli_query($conn, "SELECT * from tblstudents where status='Active'");
            $students = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body" style="background-color:#9fc1f5 ;color:#080808;border-radius:10px">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1" style="font-size:14px">Total Active Students</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                        <?php echo $students; ?>
                      </div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 20.4%</span>
                        <span>Since last month</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
             


            <!-- Std Att Card  -->
            <!-- Teachers Card  -->
            <?php
            $query1 = mysqli_query($conn, "SELECT * from tbllecturer");
            $lecture = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body" style="background-color:#9fc1f5 ;color:#080808;border-radius:10px">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1" style="font-size:14px">Total Lecturers</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo $lecture; ?>
                      </div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                    <span>Since last years</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chalkboard-teacher fa-2x text-danger"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>





            <!--Row-->

            <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>Do you like this template ? you can download from <a href="https://github.com/indrijunanda/RuangAdmin"
                  class="btn btn-primary btn-sm" target="_blank"><i class="fab fa-fw fa-github"></i>&nbsp;GitHub</a></p>
            </div>
          </div> -->
        

          
          

          </div>
          <!---Container Fluid-->
        </div>
        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
        <!-- Footer -->
      </div>
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
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>

</body>

</html>