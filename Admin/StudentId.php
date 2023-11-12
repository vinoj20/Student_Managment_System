<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';
include '../vendor/autoload.php';

if (isset($_SESSION['results'])) {
    $qry = $_SESSION['results'];
    $result = $conn->query($qry);

    while ($row = $result->fetch_assoc()) {
        $fn = substr($row['firstName'],0,1);
        $Name=$row['Title'].".".$fn.".".$row['lastName'];
        $RegNo=$row['RegNumber'];
        $Academic=$row['Academic_year'];
        $NicNo=$row['Nicno'];
        $Email=$row['email'];
        $Date=date("Y-m-d");
        $barcodedata=$NicNo;
        
        $query = "SELECT Name FROM tblprofile WHERE Student='$RegNo'";
        $rs = $conn->query($query);
        $num = $rs->num_rows;
        $rows = $rs->fetch_assoc();
        if($num>0){
            $profilename=$rows['Name'];
        }else{
            $profilename='unknown.jpg';
        }
        
        $Bar = new Picqer\Barcode\BarcodeGeneratorHTML();
        $Bar_code = $Bar->getBarcode($barcodedata, $Bar::TYPE_CODE_128);
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/logo/seu_logo.png" rel="icon">
    <title>Student Id Card</title>
    <link href="css/idcard.css" rel="stylesheet">
    <script type="text/javascript">	
 		
         window.print();
      setTimeout(function(){
        window.close()
      },750)
    </script>
</head>
<body>
    <div id='id_card'>
        <div id='id_front'>
            <div id="top">
                <div id="logo">
                    <img src="img/seu_logo.png" alt="" style="height: 69px;width: 74px;margin-left: 12px;">
                </div>
                <div id="name">
                    <p style="margin: 0px;">South Eastern University Of Sri lanka</p>
                </div>
            </div>
            <div id="details">
            <div id="profile">
                <img src="Files/students/<?php if(isset($profilename)){ echo $profilename;}?>" alt="" style="width:105px;height:140px"></div>
                <div id="about">
                <table style="height: 107px;font-size: 15px;font-weight: 700;margin: 11px;">
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <td> : <?php echo $Name; ?></td>
                            </tr>
                            <tr>
                                <td>Registration No</td>
                                <td> : <?php echo $RegNo; ?></td>
                            </tr>
                            <tr>
                                <td>Academic year</td>
                                <td> : <?php echo $Academic; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="bottom">
                <div id="signature">
				  <div style="display:flex;flex-direction:column;    display: flex;flex-direction: column;padding-top: 51px;align-items: center;">
				    <p style="width: 85px;margin-bottom: 0px;margin-top: -14px;margin-left: 9px;font-family: math;font-weight: 500;">
				   _ _ _ _ _ _ _ _<br>Signature</p>
				  </div>
				  <div style="display:flex;flex-direction:column">
				    <p style="width:160px;margin-bottom: 0px;margin-top: 37px;margin-left: 228px;font-family: math;font-weight: 500;">
					_ _ _ _ _ _ _ _ _ _ _ _ _<br>Authorized Signature</p>
				  </div>		
                </div>    
            </div>
        </div>
        <div id="id_back">
            <div id="about">
                    <table style="height: 107px;font-size: 15px;font-weight: 700;margin: 11px;">
                        <tbody>
                            <tr>
                                <td>Nic No</td>
                                <td> : <?php echo $NicNo; ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td> : <?php echo $Email; ?></td>
                            </tr>
                            <tr>
                                <td>Date issued</td>
                                <td> : <?php echo $Date; ?></td>
                            </tr>
                        </tbody>
                    </table>
            </div>
			<div id="barcode">
                <?php if(isset($Bar_code)) {echo $Bar_code;}?>
            </div>
        </div>
    </div>
    <style>
        
        #id_card {
        width: fit-content;
        height: fit-content;
        margin:60px;
        margin-left:40px;
        float: left; 		
        display: flex;
        flex-direction: column;
        }

        #id_front {
        display: flex;
        flex-direction: column;
        background-color:skyblue;
        width:480px;
        height:300px;
        position:absolute;
        opacity: 0.88;
        font-family: sans-serif;
        transition: 0.4s;
        border-radius: 2%;
        }

        #id::before {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        background-repeat:repeat-x;
        background-size: 250px 450px;
        opacity: 0.2;
        z-index: -1;
        text-align:center;
        
        }

        #id_back{
            display: flex;
            flex-direction: column;
            transition: 0.4s;
            width:480px;
            height:300px;
            background-color:skyblue;
            text-align:center;
            font-size: 16px;
            font-family: sans-serif;
            float: left;
            margin:auto;		  	
            margin-left:500px;
            border-radius:2%;
        }
        #top {
            display: flex;
            flex-direction: row;
            /* background-color: red; */
            height: 70px;
            margin: 10px;
        }

        #details {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 44%;
            border-radius: 5%;
        }

        #logo {
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 22%;
            
            
        }

        #name {
            display: flex;
            flex-direction: column;
            height: 100%;
            margin-left: 2px;
            width: 77%;
            font-size: 32px;
            margin-top: 0px;
            margin-left: -8px;
            text-align: center;
            font-family: serif;
            font-weight: 900;
        }

        #profile {
            display: flex;
            flex-direction: column;
            height: 140px;
            width: 140px;
            margin-left: 5px;
            
        }

        #about {
            display: flex;
            flex-direction: row;
            width: 100%;
            margin-left: 10px;
            font-family: math;
            
        }
        #bottom{
            display: flex;
            flex-direction: row;
            height: 78px;
            width: 100%;
            /* margin: 10px; */
            /* margin-right: -5px; */
        }
        #signature {
            display: flex;
            flex-direction: row;
            height: 100%;
            width: 27%;
        }

        #barcode {
                display: flex;
    flex-direction: row;
    width: 100%;
    margin-left: 70px;
    margin-top: 40px;
        }

    </style>
</body>
</html>
<?php }
}?>