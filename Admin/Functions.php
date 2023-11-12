<?php

//unzipfile function
function unzipFile($zipFilePath, $destinationDir) {
    $zipFilePath = escapeshellarg($zipFilePath); 
    $destinationDir = escapeshellarg($destinationDir); 
    $command = "unzip -j $zipFilePath -d $destinationDir";
    exec($command, $output, $returnCode);
}

//profile upload function
function profileupload($profilefolder,$regno,$NicNo){
    include '../Includes/dbcon.php';
    $profilefail=0;
    
    $profilepictures = scandir($profilefolder);
    $foundProfile = null;
    $allowedImageTypes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);

    //search profile
    foreach ($profilepictures as $profilepicture) {
        if ($profilepicture === '.' || $profilepicture === '..') {
            continue;
        }
        $filenameWithoutExtension = pathinfo($profilepicture, PATHINFO_FILENAME);
        $Extension = pathinfo($profilepicture, PATHINFO_EXTENSION);
        if ($filenameWithoutExtension === $NicNo) {
            // Check if the file is an allowed image type (JPEG or PNG)
            $imageType = exif_imagetype($profilefolder . DIRECTORY_SEPARATOR . $profilepicture);
            if (in_array($imageType, $allowedImageTypes)) {
                $foundProfile = $filenameWithoutExtension;
                break;
            }
        }
    }
    $sourceFilePath =$profilefolder.'/'.$foundProfile.'.'.$Extension;
    $destinationFilePath=__DIR__.'/Files/students/'. $regno.'.'.$Extension;// Replace with your desired destination directory for profile photos
    $successs=copy($sourceFilePath, $destinationFilePath);
 
    if ($successs) {
        $res = mysqli_query($conn, "select * from tblprofile where Student='$regno'");
        $ret = mysqli_fetch_array($res);
        $filename=$regno . '.' . $Extension;
        if($ret>0){
            $query1 = mysqli_query($conn, "update tblprofile set Name='$filename' where Student='$regno'");
        }else{
            $query1 = mysqli_query($conn, "INSERT INTO tblprofile(Student, Name) VALUES ('$regno', '$filename')");
        }
        return null;
    }else{
        return $NicNo;
        // $er=error_get_last();
    // echo " File moved unsuccessfully";        // echo 'roors is'.$er['message'];
    } 
       
}


function createtempfolderinserver($folderlocation){ 
    //for eg ===>   __DIR__.'/temp';
    mkdir($folderlocation,0755,true);
    return $folderlocation;
}
?>