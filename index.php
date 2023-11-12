<?php
// Include the database connection and start the session
include 'Includes/dbcon.php';
session_start();
error_reporting(0);
// Check if the login form is submitted
if (isset($_POST['login'])) {
    $userType = $_POST['userType'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform different actions based on the selected user type
    if ($userType == "Administrator") {
        // Retrieve admin data from the database
        $query = "SELECT * FROM tbladmin WHERE username = '$username'";
        $rs = $conn->query($query);
        $num = $rs->num_rows;
        $rows = $rs->fetch_assoc();
        if ($num > 0 && password_verify($password, $rows['password'])) {
            // Successful login, set session variables and redirect
            $_SESSION['userId'] = $rows['Id'];
            $_SESSION['firstName'] = $rows['firstName'];
            $_SESSION['lastName'] = $rows['lastName'];
            $_SESSION['emailAddress'] = $rows['emailAddress'];

            echo "<script type=\"text/javascript\">
                window.location = (\"Admin/index.php\");
                </script>";
        } else {
            // Invalid username/password
            $statusMsg="<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
        }
    } else if ($userType == "lecturer") {
        // Retrieve lecturer data from the database
        $query = "SELECT * FROM tbllecturer WHERE emailAddress = '$username'";
        $rs = $conn->query($query);
        $num = $rs->num_rows;
        $rows = $rs->fetch_assoc();

        if ($num > 0 && password_verify($password, $rows['password'])) {
            // Successful login, set session variables and redirect
            $_SESSION['userId'] = $rows['Id'];
            $_SESSION['firstName'] = $rows['firstName'];
            $_SESSION['lastName'] = $rows['lastName'];
            $_SESSION['emailAddress'] = $rows['emailAddress'];

            echo "<script type=\"text/javascript\">
                window.location = (\"Lecturer/index.php\");
                </script>";
        } else {
            // Invalid username/password
            $statusMsg="<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
        }
    } else if ($userType == "student") {
        // Retrieve student data from the database
        $query = "SELECT * FROM tblstudents WHERE username = '$username' AND status = 'Active'";
        $rs = $conn->query($query);
        $num = $rs->num_rows;
        $rows = $rs->fetch_assoc();

        if ($num > 0 && password_verify($password, $rows['password'])) {
            // Successful login, set session variables and redirect
            $_SESSION['userId'] = $rows['id'];
            $_SESSION['firstName'] = $rows['firstName'];
            $_SESSION['lastName'] = $rows['lastName'];
            $_SESSION['emailAddress'] = $rows['email'];
            $_SESSION['RegNumber'] = $rows['RegNumber'];

            echo "<script type=\"text/javascript\">
                window.location = (\"Students/index.php\");
                </script>";
        } else {
            // Invalid username/password
            $statusMsg="<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
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
    <link href="img/logo/attnlg.jpg" rel="icon">
    <title>FAS_SEUSL--LOGIN</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-login" style="background-image: url('img/bg_fas.jpg');">
    <!-- Login Content -->
    <div class="container-login">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card shadow-sm my-5" style="background-color:#96a6ff;opacity:0.90;border-radius:50px">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <img src="img/logo/FAS.jpg" style="width:100%;height:100px;border-radius:20px">
                                        <br><br>
                                    </div>
                                    <form class="user" method="Post" action="">
                                        <div class="form-group">
                                            <select required name="userType" class="form-control mb-3">
                                                <option value="">--Who you are--</option>
                                                <option value="Administrator">Administrator</option>
                                                <option value="lecturer">Lecturer</option>
                                                <option value="student">Student</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" required name="username"
                                                id="exampleInputEmail" placeholder="Enter your Username">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" required class="form-control"
                                                id="exampleInputPassword" placeholder="Enter Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <!-- <label class="custom-control-label" for="customCheck">Remember
                          Me</label> -->
                                            </div>
                                        </div>
                                        <?php echo $statusMsg ?>
                                        <div class="form-group">
                                            <input style="background-color:#148728" type="submit"
                                                class="btn btn-success btn-block" value="Login" name="login" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Content -->

    <!-- Scripts go here -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
</body>

</html>
