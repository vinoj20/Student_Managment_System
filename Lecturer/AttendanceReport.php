<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
$Lid=$_SESSION['userId'];

if (isset($_POST['download'])) {
    $Cid=$_POST['id'];
    $date=$_POST['date'];
 
    $query="SELECT tblsubject.code from tblsubject  WHERE tblsubject.id=$Cid ";
    $result = $conn->query($query);
    $row=$result->fetch_assoc();
    $code=$row['code'];  
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="img/logo/seu_logo.png" rel="icon">
    <title>Attendance</title>
    <script type="text/javascript">	
 		
         window.print();
      setTimeout(function(){
        window.close()
      },750)
    </script>
</head>
<body>
        <div id="content">
            <div id="table">
                    <table border="1">
                        <thead>
                            <tr style="background-color: coral;">
                              <th colspan=4; style="font-size:50px ;padding-top: 10px;padding-bottom: 10px;">Attendance Report </th>
                            </tr>
                            <tr style="background-color: cadetblue;">
                                <th colspan=2; style="text-align: left;font-size:30px;padding:10px">Course :<?php echo $code?></th>
                                <th colspan=2; style="text-align: Right;font-size:30px;padding:10px">Date :<?php echo $date; ?></th>
                            </tr>
                            <tr style="background-color: aquamarine;">
                                <th style="text-align:center;font-size:30px;padding:10px;width: 50px;">#</th>
                                <th style="text-align:center;font-size:30px;padding:10px">Reg No</th>
                                <th style="text-align:center;font-size:30px;padding:10px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_POST['download'])) {
                                $Cid=$_POST['id'];
                                $date=$_POST['date'];
                             
                                $query="SELECT tblattendance.RegNo,tblattendance.status from tblattendance 
                                        join tblsubject on tblsubject.id=tblattendance.Cid  
                                        WHERE tblattendance.dateTaken='$date' and tblattendance.Cid=$Cid and tblattendance.Lid=$Lid ";
                                $result = $conn->query($query);
                                $num=$result->num_rows;
                                $sn=0;
                                $status='';
                                if($num > 0)
                                { 
                                  while ($rows = $result->fetch_assoc())
                                    { 
                                      $sn = $sn + 1;
                                      if($rows['status']==1){
                                            $status='Present';
                                            $color='green';
                                        }else{
                                            $status='Absent';
                                            $color='red';
                                        }
                                      echo"
                                        <tr style='background-color:skyblue;text-align:center;'>
                                          <td style='font-size:20px;padding:5px'>".$sn."</td>
                                          <td style='font-size:20px;padding:5px'>".$rows['RegNo']."</td>
                                          <td style='font-size:20px;padding:5px;color:$color'>".$status."</td>
                                        </tr>";
                                    }
                                }
                            }
                            ?>
                        </tbody>
                        
                    </table>
            </div>
        </div>


<style>
    #body{
        font-family: monospace;

    }
    #content{
  
    }
    table{
        width:100%;
    }
    
</style>
</body>

</html>