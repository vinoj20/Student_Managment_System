<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
$Lid=$_SESSION['userId'];


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
    function getSelectedValue(str) {
        if (str == "") {
            document.getElementById("courses").innerHTML = "";
            return;
        } else { 
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("courses").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET","dropdown.php?year="+str,true);
            xmlhttp.send();
         }
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
            <h1 class="h3 mb-0 text-gray-800">Download Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Download Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Download Attendance</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post" action="AttendanceReport.php" target="_blank">
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Select Year<span class="text-danger ml-2">*</span></label>
                            <?php
                            $qry = "SELECT * from tblyear ";
                            $result = $conn->query($qry);
                            $num = $result->num_rows;
                            if ($num > 0) {
                            echo ' <select required name="id" onchange="getSelectedValue(this.value)" class="form-control mb-3">';
                            echo '<option value="">--Select Year--</option>';
                            while ($rows = $result->fetch_assoc()) {                            
                                echo '<option value="' . $rows['Id'] . '" >'.$rows['Year'].'</option>';
                            
                            }
                            echo '</select>';
                            } 
                            ?>
                        </div>
                        <div class="col-xl-6">
                            <?php 
                            echo "<div id='courses'></div>"
                            ?>         
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Select Date<span class="text-danger ml-2">*</span></label>
                        <input required name="date" type="date" class="form-control" data-zdp_readonly_element="false" style='width:150px;'>
                        </div>    
                    </div> 
                    <button type="submit" name="download" class="btn btn-primary">download</button>
                  </form>
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