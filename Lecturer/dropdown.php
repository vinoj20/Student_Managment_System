<?php
error_reporting(1);
include '../Includes/dbcon.php';
include '../Includes/session.php';
$Lid=$_SESSION['userId'];

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['option'])) {

    $option = $_GET['option'];
    
    switch($option){
      case 1:
        $qry ="SELECT tblsubject.id,tblsubject.code,tblsubject.Name,tblsubject.year from tblsubject 
        join tbllectureenroll on tbllectureenroll.Cid=tblsubject.id 
        join tblacademic on tblacademic.semesterId=tblsubject.semester 
        WHERE tbllectureenroll.Lid=$Lid and tblacademic.isActive='Active'";
        break;
      case 2:
        $qry ="SELECT tblsubject.id,tblsubject.code,tblsubject.Name,tblsubject.year from tblsubject 
        join tbllectureenroll on tbllectureenroll.Cid=tblsubject.id 
        WHERE tbllectureenroll.Lid=$Lid";
        break;
      }
      $result = $conn->query($qry);
      $num = $result->num_rows;
      if ($num > 0) {
        echo '<label class="form-control-label">Select Courses <span class="text-danger ml-2">*</span></label>';
        echo ' <select required name="id" class="form-control mb-3">';
        echo '<option value="non">--Select Course--</option>';
        while ($rows = $result->fetch_assoc()) {
          echo '<option value="' . $rows['id'] . '" >' . $rows['code'] . " " . $rows['Name'] . '</option>';
          
        }
        echo '</select>';
      } else {
        echo '<label class="form-control-label">Select Courses <span class="text-danger ml-2">*</span></label>';
        echo ' <select required name="" class="form-control mb-3">';
        echo '<option value="">--Select Course--</option>';
        echo '<option>No Course Availables</option>';
        echo '</select>';
      }
  
  }

  if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['year'])) {
    $year = $_GET['year'];
    $qry = "SELECT tblsubject.id,tblsubject.code,tblsubject.Name from tbllectureenroll 
            join tblsubject on tblsubject.id=tbllectureenroll.Cid 
            WHERE tbllectureenroll.Lid=$Lid and tblsubject.year=$year";

    $result = $conn->query($qry);
    $num = $result->num_rows;
    if ($num > 0) {
      echo '<label class="form-control-label">Select Courses <span class="text-danger ml-2">*</span></label>';
      echo ' <select required name="id" class="form-control mb-3">';
      echo '<option value="">--Select Course--</option>';
      while ($rows = $result->fetch_assoc()) {
        $CourseN=$rows['code'] . " " . $rows['Name'] ;
        
        echo '<option value="' . $rows['id'] . '" >'.$CourseN.'</option>';
        
      }
      echo '</select>';
    } else {
      echo '<label class="form-control-label">Select Courses <span class="text-danger ml-2">*</span></label>';
      echo ' <select required name="" class="form-control mb-3">';
      echo '<option value="">--Select Course--</option>';
      echo '<option>No Course Availables</option>';
      echo '</select>';
    }

  }  
?>