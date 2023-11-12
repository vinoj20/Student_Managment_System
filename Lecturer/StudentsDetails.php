
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
$Lid=$_SESSION['userId'];

if (isset($_POST['view'])) {
  $check = 1;
  $C= $_POST['id'];
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
  </script>
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
       <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Students Details</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Students details</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Students details</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
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

       
                        <!-- <div class="col-xl-6">
                        
                        </div> -->
                    </div>
                    <button type="submit" name="view" class="btn btn-primary">Proceed</button>
                  </form>
                </div>
              </div>
              <?php if (isset($check)) { ?>
              <!-- Input Group -->
                 <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Enrolled Student details for <?php echo $N ?></h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Reg Number</th>
                        <th>Email</th>
                      </tr>
                    </thead>
                   
                    <tbody>

                  <?php
                    if(isset($_POST['view'])){
                      $Cid=$_POST['id'];
                      $query = "SELECT tblsubject.code,tblsubject.Name,tblstudents.Title,tblstudents.firstName,tblstudents.lastName,tblstudents.RegNumber,tblstudents.email from tblstudents join tblstudentenroll on tblstudents.RegNumber=tblstudentenroll.SRegNumber join tblsubject on tblstudentenroll.Cid=tblsubject.id WHERE tblsubject.id=$Cid";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      $status="";
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                  
                            $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['Title'].".".$rows['firstName']." ".$rows['lastName']."</td>
                                <td>".$rows['RegNumber']."</td>
                                <td>".$rows['email']."</td>
                              </tr>";
                          }
                      }
                      else {
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
       <?php include "Includes/footer.php";?>
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