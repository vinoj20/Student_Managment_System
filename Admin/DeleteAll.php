<?php
// Enable error reporting for development
error_reporting(0);
//ini_set('display_errors', 1);

include '../Includes/dbcon.php';

if (isset($_GET['option']) && $_GET['option'] && isset($_GET['action']) && $_GET['action'] == "deleteAll") {
    $option = $_GET['option'];
    $statusMsg = "";

    switch ($option) {
        case "student":
            $query = mysqli_query($conn, "DELETE FROM tblstudents");
            if ($query) {
                $que = mysqli_query($conn, "DELETE FROM tblstudentenroll");
                $que = mysqli_query($conn, "DELETE FROM tblprofile");
                echo "<script type = \"text/javascript\">
                window.location = (\"Students.php\")
                </script>";
            } else {
                $statusMsg = "<div class='alert alert-danger' id='msg' style='margin-right:700px;'>An error Occurred!</div>";
            }
            break;

        case "lecturer":
            $query = mysqli_query($conn, "DELETE FROM tbllecturer");
            if ($query) {
                $query = "SELECT * FROM tbllectureenroll";
                $rs = $conn->query($query);
                $num = $rs->num_rows;

                while ($row = $rs->fetch_assoc()) {
                    $Cid = $row['Cid'];
                    // Update the tblsubject for the current Cid
                    $qu = mysqli_query($conn, "UPDATE tblsubject SET isAssigned='0' WHERE id='$Cid'");
                }

                $qu1 = mysqli_query($conn, "DELETE FROM tbllectureenroll");
                if ($qu1) {
                  echo "<script type = \"text/javascript\">
                  window.location = (\"Lecturer.php\")
                  </script>";
                } else {
                  echo "<script type = \"text/javascript\">
                  window.location = (\"Lecturer.php\")
                  </script>";
                }
            } else {
              echo "<script type = \"text/javascript\">
              window.location = (\"Lecturer.php\")
              </script>";
            }
            break;

        case "course":
            $query = mysqli_query($conn, "DELETE FROM tblsubject");
            if ($query) {
                $que = mysqli_query($conn, "DELETE FROM tblstudentenroll");
                $que = mysqli_query($conn, "DELETE FROM tbllectureenroll");
                $que = mysqli_query($conn, "DELETE FROM tblhoursremain");
                echo "<script type = \"text/javascript\">
                  window.location = (\"Course.php\")
                  </script>";
            } else {
              echo "<script type = \"text/javascript\">
              window.location = (\"Course.php\")
              </script>";
            }
            break;

        case "all":
            // Handle delete all option if needed
            break;
    }
}
?>
