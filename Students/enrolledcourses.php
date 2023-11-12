<?php
$reg = $_SESSION['RegNumber'];

?>
<!-- All enrolled Courses-->
<div class="row">
    <div class="col-lg-12">
        <!-- Form Basic -->
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">All Enrolled Courses</h6>
                <?php echo $statusMsg; ?>
            </div>
            <div class="card-body">
                <?php
                $Q = "Select tblsubject.id,tblsubject.code,tblsubject.Name from tblsubject join tblstudentenroll on tblsubject.id=tblstudentenroll.Cid where SRegNumber='$reg'";
                $result = $conn->query($Q);
                $n = $num = $result->num_rows;
                if ($n > 0) {
                    echo '<ul class="m-0 font-weight-bold text-primary" style="font-size:20px;">';
                    while ($rows = $result->fetch_assoc()) {
                        $N = $rows['code'] . " " . $rows['Name'];
                        echo '<a href="coursedetails.php?Cid=' . $rows['id'] . '&name=' . $N . '"><li>' . $N . '</li></a>';
                    }
                    echo '</ul>';

                } else {
                    echo '<h6 class="m-0 font-weight-bold text-primary" style="font-size:30px;">You didnt enroll any courses!</h6>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- All enrolled Courses-->