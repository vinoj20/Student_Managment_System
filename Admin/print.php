<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['academic_year'])) {
    // Retrieve the selected academic year from the AJAX request
    $AcademicYear = $_GET['academic_year'];
     
    if($AcademicYear=="All"){
        $qry = "SELECT * FROM tblstudents where status='Active'";
    }else{
        $qry = "SELECT * FROM tblstudents where status='Active' and Academic_year='$AcademicYear' ORDER BY id ASC";
    }
    $result = $conn->query($qry);
    echo '<label class="form-control-label">Select Student <span class="text-danger ml-2">*</span></label>';
    echo '<select required name="student" class="form-control mb-3">';
    echo '<option value="">--Select Students--</option>';
    echo '<option value="All">Print All students ID Card</option>';
    //echo '<option value="custom">Print All students ID Card</option>';
    while ($rows = $result->fetch_assoc()) {
        echo '<option value="' . $rows['RegNumber'] . '">' . $rows['RegNumber'] . " " . $rows['firstName'] . " " . $rows['lastName'] . '</option>';
    }
    echo '</select>';
}

if (isset($_GET['academic']) and $_GET['student'] ) {
    $AcademicYear=$_GET['academic'];
    $option=$_GET['student'];
    
    switch (true) {
        case ($AcademicYear == "All" && $option == "All"):
            // Code to execute when both $AcademicYear and $option are "All"
            $qry = "SELECT * FROM tblstudents where status='Active'";
            break;
            
        case ($AcademicYear == "All"):
            // Code to execute when $AcademicYear is "All" but $option is not "All"
            $qry = "SELECT * FROM tblstudents where status='Active' and RegNumber='$option'";
            break;
            
        case ($option == "All"):
            // Code to execute when $option is "All" but $AcademicYear is not "All"
            $qry = "SELECT * FROM tblstudents where status='Active' and Academic_year='$AcademicYear'";
            break;
            
        default:
            // Code to execute when both $AcademicYear and $option are not "All"
            $qry = "SELECT * FROM tblstudents where status='Active' and RegNumber='$option' and Academic_year='$AcademicYear'";
            
    }
    $result = $conn->query($qry);
    $num = $result->num_rows;
    if ($num > 0) {
        $_SESSION['results'] = $qry;
        echo "<script type=\"text/javascript\">
        window.location=(\"StudentId.php\");
    </script>";
    
    }
}

?>