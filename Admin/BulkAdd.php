<?php
error_reporting(0);

// Include necessary files (dbcon.php and session.php)
include '../Includes/dbcon.php';
include '../Includes/session.php';
include 'functions.php';
$statusMsg = ''; // Initialize status message variable

//check
if(isset($_GET['fileadd'])){
  $fileadd=1;
}elseif(isset($_GET['profileadd'])){
  $profileadd=1;
}

//download sample file
if(isset($_GET['File_id'])){
  $File_id = $_GET['File_id'];
  $query = "SELECT Name, Type FROM tblfiles WHERE id='$File_id'";
  $rs = $conn->query($query);
  $num = $rs->num_rows;

  if ($num > 0) {
    $rows = $rs->fetch_assoc();
    $Filename = $rows['Name'];
    $type = $rows['Type'];
    $path = 'Files/' . $Filename;

    // Check if the file exists
    if (file_exists($path)) {
        // Set appropriate headers
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($Filename) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Content-Length: ' . filesize($path)); // Add content length header

        // Prevent any output before file download
        ob_clean();
        flush();

        // Open the file and stream its content to the browser
        $file = fopen($path, 'rb');
        while (!feof($file)) {
            // Output file in chunks to handle large files efficiently
            echo fread($file, 4096);
        }
        fclose($file);
        exit;
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>File not found at path: $path</div>";
    }
  } else {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>File record not found in the database!</div>";
  }
}

//insert data
if (isset($_POST['import'])) {
    // Initialize variables
    $result=array();
    $existing = array("Failed_Details_You_need_to_Correct_and_Upload_Again_(Don't_Use_previous_Sheet)");
    $failprofilelist = array("Failed_profile_Details_with_regno_(unmatched_with_Nic_number)_these_people_listed_umknown");
    $option = $_POST['option'];
    $dateCreated  = $_POST['date'];

    // Read the uploaded CSV file
    $file = $_FILES['file']['tmp_name'];
    $uploadedFilename = $_FILES['file']['name'];
    $handle = fopen($file, "r");
    
    //To upload profile folder
    if(isset($_FILES['profile']['tmp_name'])){
      $yesyoucanuploadprofile=0;
      $zip=$_FILES['profile']['tmp_name'];
      $isZip=pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
      $profilefolder=createtempfolderinserver(__DIR__.'/Files/tempprofile');
      unzipFile($zip,$profilefolder);
      $containitems = scandir($profilefolder);
      //echo count($containitems);
      if($isZip=='zip' and count($containitems)>2){
      $yesyoucanuploadprofile=1;
    }

    }
    
    // Initialize counters
    $count = 0;
    $success = 0;
    $fail = 0;

    switch ($option) {
        case "Student":
          if($uploadedFilename=='Students.csv'){
            while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
              $count++;

              // Skip the header row
              if ($count === 1) {
                  continue;
              }

              // Extract data from the CSV row
              list($Title, $firstName, $lastName, $NicNo, $email, $Academic_year) = $filesop;
              $sampPass = $NicNo;
              $sampPass_2 = password_hash($sampPass, PASSWORD_DEFAULT); // Securely hash the password

              // Check if the student already exists in the database
              $query = mysqli_query($conn, "SELECT * FROM tblstudents WHERE Nicno ='$NicNo'");
              $ret = mysqli_fetch_array($query);
              if ($ret > 0) {
                  // Student already exists, add NIC number to the existingStudents array
                  array_push($existing, $NicNo);
                  $fail++;
                  continue;
              } else {
                  // Insert the student data into the database
                  $query = mysqli_query($conn, "SELECT * FROM tblstudents where Academic_year='$Academic_year'");
                  $rowcount = mysqli_num_rows($query);
                  $id = $rowcount + 1;
                  $id4 = sprintf('%04d', $id);
                  $ac = substr($Academic_year, 2, 2);
                  $ac = (int) $ac;
                  $regno = "SEU" . $ac . "FAS" . $id4;
                  $query = mysqli_query($conn, "INSERT INTO tblstudents(id, Title, firstName, lastName, Academic_year, RegNumber, Nicno, email, username, password, dateCreated, CourseEnrollStatus, status) 
                  VALUES ('$id', '$Title', '$firstName', '$lastName', '$Academic_year', '$regno', '$NicNo', '$email', '$regno', '$sampPass_2', '$dateCreated', '0', 'Active')");

                  // Move profile photo to the destination directory
                  if($query and $yesyoucanuploadprofile==1){
                    if(isset($profilefolder)){
                      $failNic=profileupload($profilefolder,$regno,$NicNo);
                      
                      if($failNic!=null){
                        array_push($failprofilelist,$failNic);      
                      }
                    }else{
                    }
                  }else{
                    $profileuploadfail_notzip=1;
                  }
                
                }  
              $success++;      
            }        
          // Generate status message and error list file
            if(count($existing)==1 and count($failprofilelist)==1){
              array_push($result,"All_details_are_insert_Succesfully_but_profile_fail......");
            }else if(count($existing)==1 and count($failprofilelist)==1){
              array_push($result,"All_details_are_insert_Succesfully_including_Profile_also......");
            }else if(count($existing)==1 and count($failprofilelist)>1){
              array_push($result,"All_Students_details_are_inserted_Succesfully_but_some_profile_not...."," ");
              $result=array_merge($result,$failprofilelist);
            }else if(count($existing)>1 and count($failprofilelist)==1){
              $result=array_merge($result,$existing);
            }else{
              $result=array_merge($result,$existing);
              $result=array_merge($result,$failprofilelist);  
            }
            if($yesyoucanuploadprofile==0){
              array_push($result,"____","Profile_upload_fail_problem_in_your_Zip-file_you_can_add_profile_separately(Sometime_you_did_a_mistake_in_archive_the_file_--for_eg_archive_the_profilepictures_not_contain_folder)");
              array_map('unlink',glob("$profilefolder/*.*"));
              rmdir($profilefolder);
            }
            $fileContent = implode("\n", $result);
            $statusMsg =
                "<div class='alert alert-success' style='margin-right: 700px; width:750px;background-color:green;'>DONE
                  <a href='data:text/plain;charset=utf-8," . urlencode($fileContent) . "' download='result.txt' style='background-color:white'>Click to download the status  list </a></div>";
        }else{
            $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>Choose Correct file or Correct the file </div>";
        }
            // Process each line of the CSV file
           
            break;

    case "Lecturer":
      if($uploadedFilename=='Lecturer.csv'){
        // Process each line of the CSV file
        while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
              $count++;
              // Skip the header row
              if ($count === 1) {
                  continue;
              }
              // Extract data from the CSV row
              list($Title, $firstName, $lastName, $NicNo, $email, $Phone_no) = $filesop;
              $sampPass = "FASL$NicNo";
              $sampPass_2 = password_hash($sampPass, PASSWORD_DEFAULT); // Securely hash the password
              // Check if the Lecturer already exists in the database
              $query = mysqli_query($conn, "SELECT * FROM tbllecturer WHERE Nicno ='$NicNo' OR emailAddress='$email'");
              $ret = mysqli_fetch_array($query);
              if ($ret > 0) {
                  // Student already exists, add NIC number to the existingStudents array
                  array_push($existing, $NicNo);
                  $fail++;
                  continue;
              } else {
                $query = mysqli_query($conn, "select * from tbllecturer");
                $rowcount = mysqli_num_rows($query);
                $id = $rowcount + 1;
            
                $query1 = mysqli_query($conn, "INSERT into tbllecturer(Id,Title,firstName,lastName,emailAddress,username,password,phoneNo,dateCreated,Nicno) 
                value('$id','$Title','$firstName','$lastName','$email','$email','$sampPass_2','$Phone_no','$dateCreated','$NicNo')");
                  $success++;
              }
        }
        // Generate status message and error list file
        if ($fail > 0) {
            $fileContent = implode("\n", $existing);
            if($success==0){

              $statusMsg =
                "<div class='alert alert-danger' style='margin-right: 700px; width:750px;background-color:#fc5d5d;'>
                $fail Lecturer details already occurred!  
                <a href='data:text/plain;charset=utf-8," . urlencode($fileContent) . "' download='Error.txt'>Click to download the list</a></div>";

            }else{
              $statusMsg =
                "<div class='alert alert-danger' style='margin-right: 700px; width:750px;background-color:#fc5d5d;'>
                $fail Lecturer details already occurred, <p style:'background-color:green;'> other $success details inserted successfully</p>
                <a href='data:text/plain;charset=utf-8," . urlencode($fileContent) . "' download='Error.txt'>Click to download the list</a></div>";

            }

            
        } else {
            $statusMsg = "<div class='alert alert-success' style='margin-right: 700px; width:750px;background-color:green;'>$success details inserted Successfully</div>";
        }
      }else{
        $statusMsg = "<div class='alert alert-danger'  style='margin-right:700px;'>Choose Correct file</div>";
      }  
      break;
    
    case "Course":
      if($uploadedFilename=='Course.csv'){
      // Process each line of the CSV file
        while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
            $count++;

            // Skip the header row
            if ($count === 1) {
                continue;
            }
            // Extract data from the CSV row
            list($code, $Name, $type, $subjectname, $year, $semester,$nh) = $filesop;    
            // Check if the student already exists in the database
            $query = mysqli_query($conn, "select * from tblsubject where code ='$code'");
            $ret = mysqli_fetch_array($query);
            if ($ret > 0) {
                // Student already exists, add NIC number to the existingStudents array
                array_push($existing, $code);
                $fail++;
                continue;
            } else {
                // Insert the student data into the database
                $query = mysqli_query($conn, "select * from tblsubject");
                $rowcount = mysqli_num_rows($query);
                $id = $rowcount + 1;

                $query1 = mysqli_query($conn, "INSERT into tblsubject(id,code,Name,Type,SubjectName,year,semester,NoofHours,isAssigned,DateAdded) 
                value('$id','$code','$Name','$type','$subjectname','$year','$semester','$nh','0','$dateCreated')");
                $success++;
            }
        }
        // Generate status message and error list file
        if ($fail > 0) {
          $fileContent = implode("\n", $existing);
          if($success==0){
            $statusMsg =
              "<div class='alert alert-danger' style='margin-right: 700px; width:750px;background-color:#fc5d5d;'>
              $fail Course details already occurred!  
              <a href='data:text/plain;charset=utf-8," . urlencode($fileContent) . "' download='Error.txt'>Click to download the list</a></div>";
          }else{
            $statusMsg =
              "<div class='alert alert-danger' style='margin-right: 700px; width:750px;background-color:#fc5d5d;'>
              $fail Course details already occurred, <p style:'background-color:green;'> other $success details inserted successfully</p>
              <a href='data:text/plain;charset=utf-8," . urlencode($fileContent) . "' download='Error.txt'>Click to download the list</a></div>";
          }
        } else {
            $statusMsg = "<div class='alert alert-success' style='margin-right: 700px; width:750px;background-color:green;'>$success inserted Successfully</div>";
        }
      }else{
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Choose Correct file</div>";
      }
        break;
  }

  array_map('unlink',glob("$profilefolder/*.*"));
  rmdir($profilefolder);
  
} 

//insert profile
if (isset($_POST['importprofile'])){
  $result=array();
  $failprofilelist = array("Failed_profile_Details_with_regno_(unmatched_with_Nic_number)_these_people_listed_umknown");
  $yesyoucanuploadprofile=0;
  $zip=$_FILES['profile']['tmp_name'];
  $isZip=pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
  $profilefolder=createtempfolderinserver(__DIR__.'/Files/tempprofile');
  unzipFile($zip,$profilefolder);
  $containitems = scandir($profilefolder);
  //echo count($containitems);
    if($isZip=='zip' and count($containitems)>2){
    $yesyoucanuploadprofile=1;
    }

  if($yesyoucanuploadprofile==1 and isset($profilefolder)){
    $query = "SELECT * from tblstudents where status='Active'";
    $rs = $conn->query($query);
    $num = $rs->num_rows;
    
    if($num>0){
      while($row = $rs->fetch_assoc()){
        $regno=$row['RegNumber'];
        $NicNo=$row['Nicno'];
        $failNic=profileupload($profilefolder,$regno,$NicNo);
        if($failNic!=null){
          array_push($failprofilelist,$failNic);      
        }
      }
      
      //genarate result
      if(count($failprofilelist)>1){
        $result=array_merge($result,$failprofilelist);  
      }else{
        array_push($result,"All_prfiles_are_insert_Succesfully.....");
      }
      $fileContent = implode("\n", $result);
      $statusMsg =
          "<div class='alert alert-success' style='margin-right: 700px; width:750px;background-color:green;'>DONE
            <a href='data:text/plain;charset=utf-8," . urlencode($fileContent) . "' download='result.txt' style='background-color:white'>Click to download the status  list </a></div>";
    }else{
      //no students registeryet
      $statusMsg =
          "<div class='alert alert-danger' id='msg' style='margin-right: 700px; width:750px;'>No Students Register Yet!</div>";
    }   
  }else{
    //faultin folder or folder creation
    "<div class='alert alert-danger' id='msg' style='margin-right: 700px; width:750px;'>Problem in Server!</div>";
  }     
array_map('unlink',glob("$profilefolder/*.*"));
rmdir($profilefolder);  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta tags and CSS files -->
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
  <script>
      $(document).ready(function () {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      });

      function downloadFile(content, filename) {
  
        const blob = new Blob([content], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        URL.revokeObjectURL(url);
      }
      if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}
    </script>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
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
            <!-- Breadcrumbs -->
            <h1 class="h3 mb-0 text-gray-800">Bulk registration upload from Excel</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Bulk Add</li>
            </ol>
            <!-- End Breadcrumbs -->
          </div>

          <!-- Steps and Information Section -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="mid-content-top charts-grids" style="color: orangered;background-color:lightblue;border-radius: 10px;margin: 8px;padding: 8px;">
                    <div class="middle-content">
                        <!-- Steps to save the file -->
                        <h4 class="title"><i class="fa fa-info-circle"></i>&nbsp;Steps to Insert Via file!</h4>
                        <div class="container">
                            <ol>
                                <li>Download the sample file format from here</li>
                                <div id="file-download">
                                    <a href="BulkAdd.php?File_id=1"><i class="fas fa-user-graduate"></i>Students</a>
                                    <a href="BulkAdd.php?File_id=2"><i class="fas fa-chalkboard-teacher"></i> Lecturers</a>
                                    <a href="BulkAdd.php?File_id=3"><i class="fas fa-chalkboard"></i> Courses</a>
                                </div>
                                <li>Fill the details in the columns of the file</li>
                                <li>column details</li>
                                    <table style="margin-top: 5px;width: 1143px;color: black;font-weight: bold;background-color: snow;border-radius: 5%;">
                                      <tbody>
                                        <tr style="color: red;"><th>Students sheet</th><th>Details</th><th>Course sheet</th><th>Details</th></tr>
                                        <tr><td style="color: red;">Title</td><td>Mr or Miss or Mrs or Dr or Prof</td><td style="color: red;">Type</td><td>Main or Combulsary or Elective or Auxilary</td></tr>
                                        <tr><td style="color: red;">Academic year</td><td>for eg:- 2018/2019</td><td style="color: red;">Subject</td><td>Mathematics or Computer Science Or Biology Or Physics or Chemistry Or Applied Statitics Or Earth Science Or Other </td></tr>
                                      </tbody>
                                    </table>
                                <li>Save the file as CSV not as xls</li>
                                <li>Upload the file , Make sure Choose Correct option in Form Selection (Students Or Lecturers Or Courses)</li>
                                <li>If you wish you can add profile pictures also,to this You need change the picture name as student's Nic No and select convert into .zip format and upload or you can add profile folder later</li>
                                
                            </ol>
                        </div>
                        <!-- End Steps to save the file -->
                        <h5>SAMPLE Note: The web as file type will only be noted on excel files download from this application</h5>
                        <div id="filess">
                            <h5>Are you Ready with Your files if yes <a href='BulkAdd.php?fileadd#upload'>click here to Add</a></h5>
                            <h5>if you add profile pictures zip only <a href='BulkAdd.php?profileadd#profileupload'>click here to Add Profiles</a></h5>   
                        </div>
                        
                    </div>
                </div>
            </div>
            </div>
          </div>

          
          <!-- End Steps and Information Section -->

          <!-- Data Insertion Form Section -->
          <?php if(isset($fileadd)){ ?>
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Insert Data</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="alert alert-success" style="width:42%;border-radius: 10px;margin: 8px;padding: 8px;">
                <i class="fa fa-info-circle"></i>&nbsp;Ensure that the file upload is in CSV Format Otherwise it will not save
                </div>
                <div style="width:42%;border-radius: 10px;margin: 8px;padding: 8px;">
                </div>
                <div class="card-body">
                  <form method="post" enctype='multipart/form-data' id='upload'>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Students / Lecturers / Courses</label><span class="text-danger ml-2">*</span>
                        <select required name="option" class="form-control mb-3">
                            <option value="">--Choose option--</option>
                            <option value="Student" >Students</option>
                            <option value="Lecturer">Lecturers</option>
                            <option value="Course">Courses</option>
                        </select>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Submission Date</label><span class="text-danger ml-2">*</span>
                        <input required name="date" type="date" class="form-control" data-zdp_readonly_element="false" style='width:255px;'>
                      </div>
                      <div class="col-xl-6">
                      <label class="form-control-label">Select Updated Sheet</label><span class="text-danger ml-2">*</span>
                      <input name='file' type='file' id='file' required>
                      </div>
                      <div class="col-xl-6">
                      <label class="form-control-label">Select Profile Photos contain Zip file</label><span class="text-danger ml-2">*</span>
                      <input name='profile' type='file' id='profile'>
                      </div>
                    </div>
                    <!-- Add other form fields here -->
                    <button type="submit" name="import" class="btn btn-primary" >Import</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>

          <?php if(isset($profileadd)){ ?>
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Add profiles</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="alert alert-success" style="width:42%;border-radius: 10px;margin: 8px;padding: 8px;">
                <i class="fa fa-info-circle"></i>&nbsp;Ensure that the file upload is in Zip Format Otherwise it will not save
                </div>
                <div style="width:42%;border-radius: 10px;margin: 8px;padding: 8px;">
                </div>
                <div class="card-body">
                  <form method="post" enctype='multipart/form-data' id='profileupload'>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                      <label class="form-control-label">Select Profile Photos contain Zip File</label><span class="text-danger ml-2">*</span>
                      <input name='profile' type='file' id='profile'>
                      </div>
                    </div>
                    <!-- Add other form fields here -->
                    <button type="submit" name="importprofile" class="btn btn-primary" >Import</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
          <!-- End Data Insertion Form Section -->
        </div>
        <!-- End Container Fluid -->

        <!-- Footer -->
        <?php include "Includes/footer.php"; ?>
        <!-- End Footer -->
      </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- JavaScript Files -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="js/zebra_datepicker.min.js"></script>
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>  
    <!-- Page level custom scripts -->
    
    <style>
        #file-download a {
  padding-right: 10px;
  background-color: azure;
  text-decoration: none;
  margin: 6px;
  border-radius: 15px;
  font-size: 16px;
  padding-left: 10px;
  color: midnightblue;
}
#file-download a:hover {
    background-color: azure;
    color: blue;
    font-weight: bold;
}

#filess{
  margin: 12px;
  padding: 10px;
  width:50%;
  color: black;
  font-family: cursive;
  background-color: whitesmoke;
  border-radius: 10px;
    
}
    </style>
</body>
</html>
