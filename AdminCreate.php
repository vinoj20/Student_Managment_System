<?php 
include 'Includes/dbcon.php';

if(isset($_POST['save'])){
    $fname=$_POST['fname'];
    $query = mysqli_query($conn, "select * from tbladmin");
    $rowcount = mysqli_num_rows($query);
    $id = $rowcount + 1;
    $username="FasAdmin_$id";
    $password="FasAdmin_$id";
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $query1 = mysqli_query($conn, "insert into tbladmin(Id,firstName,lastName,username,password) 
    value('$id','$fname','FAS','$username','$hashedPassword')");
    if($query1){
        echo "Admin created Succesfully <br>";
        echo "Admin No: $id <br>";
        echo "username: $username <br>";
        echo "Password: $password <br>";

    }
    

}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Creation</title>
    <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</head>
<body>
    <form method="POST" action="" enctype='multipart/form-data'>
        <table>
            <tr>
                <td><label for="fname">FirstName</label></td>
                <td><input type="text" name='fname'></td>
            </tr>
        </table>
        <input type="submit" name='save'>
    </form>
    
</body>
</html>