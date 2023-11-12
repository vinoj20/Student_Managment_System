<?php
error_reporting(0);

// Including necessary files
include '../Includes/dbcon.php';
include '../Includes/session.php';

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
    <script src="../vendor/jquery/jquery.min.js"></script>

    <script src="js/ruang-admin.js"></script>
    <!-- Page level plugins -->

    <script>
        // Function to handle AJAX request and update the page
        function getSelectedValue(str) {
        if (str == "") {
            document.getElementById("students").innerHTML = "";
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
                    document.getElementById("students").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET","print.php?academic_year="+str,true);
            xmlhttp.send();
    }
}
    </script>
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

                <!-- Container Fluid -->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Get Student Id Card</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Get Id</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Basic -->
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Print Student Id Card</h6>
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="print.php" target="_blank">
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <?php
                                                echo '<label class="form-control-label">Select Academic Year<span class="text-danger ml-2">*</span></label>';
                                                $qry = "SELECT DISTINCT Academic_year FROM tblstudents WHERE status='Active'";
                                                $result = $conn->query($qry);
                                                $num = $result->num_rows;
                                                echo '<select required name="academic" class="form-control mb-3" onchange="getSelectedValue(this.value)">';
                                                echo '<option value="">--Select Academic Year-</option>';
                                                
                                                $qr = "SELECT * FROM tblstudents WHERE status='Active'";
                                                $resul = $conn->query($qr);
                                                $num = $resul->num_rows;
                                                if($num>0){
                                                    echo '<option value="All">All</option>';
                                                    while ($rows = $result->fetch_assoc()) {
                                                        echo '<option value="' . $rows['Academic_year'] . '">' . $rows['Academic_year'] . '</option>';
                                                    }
                                                    echo '</select>';   
                                                }else{
                                                    echo '<option value="" selected>No Students Available......</option>';
                                                }
                                                

                                                ?>
                                            </div>
                                            <div class="col-xl-6">
                                                <?php 
                                                echo "<div id='students'></div>"
                                                ?>         
                                            </div>
                                        </div>
                                        <button type="submit" name="Print" class="btn btn-primary">Print</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>  
</body>
</html>
