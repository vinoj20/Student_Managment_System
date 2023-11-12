<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
$reg = $_SESSION['RegNumber'];
$check="cw";

if (isset($_POST['ok'])) {
  $op=$_POST['op'];
  $Q = "Select * from tblstudents where RegNumber='$reg'";
  $result = $conn->query($Q);
  $num = $result->num_rows;
  $rows = $result->fetch_assoc();
  if ($num > 0 && password_verify($op, $rows['password'])) {
    $check="cp";
  } else {
    $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>Incorrect Password</div>";
  }
}

if (isset($_POST['change'])) {
    $pwn=$_POST['np'];
    $pwc=$_POST['cp'];

    if($pwn==$pwc){
        $np=password_hash($pwn,PASSWORD_DEFAULT);
        $Q = mysqli_query($conn,"update tblstudents set password='$np' where RegNumber='$reg'");
        if ($Q) {
          $statusMsg = "<div class='alert alert-success' id='msg' style='margin-right:700px;'>Password change succesfully!</div>";
          $check='cw';
          } else {
            $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
          }
    }else{
        $statusMsg = "<div class='alert alert-danger'  style='margin-right:700px;'>Passwords doesn't match Try Again!</div>";
        $check="cp";
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
  <script src="js/ruang-admin.js"></script>
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
            <h1 class="h3 mb-0 text-gray-800">Change Password</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">change password</li>
            </ol>
          </div>
          
          <!--check Current password -->
          <?php if($check=="cw"){ ?>
          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                 
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                    <?php echo $statusMsg; ?>
                      <div class="col-xl-6">
                        <label class="form-control-label">Enter Current Password<span class="text-danger ml-2">*</span></label>
                        <input type="password" class="form-control" required name="op" id="">
                      </div>
                    </div>
                    <button type="submit" name="ok" class="btn btn-primary">Proceed</button>
                  </form>
                </div>
              </div>
          <?php } ?>
           <!--change Current password -->
        <?php if($check=="cp"){ ?>
          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <?php echo $statusMsg; ?>
                  <h6 class="m-0 font-weight-bold text-primary">Create New Password</h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                    <?php echo $statusMsg; ?>
                      <div class="col-xl-6">
                        <label class="form-control-label">Enter New Password<span class="text-danger ml-2">*</span></label>
                        <input type="password" class="form-control" required name="np" id="np" 
                        value="">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Confirm Password<span class="text-danger ml-2">*</span></label>
                        <input type="password" class="form-control" required name="cp" id="cp">
                      </div>
                    </div>
                    <button type="submit" name="change" class="btn btn-primary">Proceed</button>
                  </form>
                </div>
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

          <!-- Page level custom scripts -->

</body>

</html>