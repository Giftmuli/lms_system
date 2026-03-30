<?php
include('dbcon.php');
include('header.php');

if(!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "Please login to access the dashboard";
    header("Location: login.php");
    exit(0);
}
?>

    <div class ="container my-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4>Student Dashboard</h4>
            </div>
            <div class="card-body">
                <h5>Welcome, <?=$_SESSION['auth_user']['user_name']?></h5>
                <p>Role: <span class="badge bg-info text-dark"><?=$_SESSION['auth_role']; ?></span></p>
                <hr>
                <div class="alert alert-light border">
                    <h6>Available Courses</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mt-3">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $query = "SELECT * FROM courses";
                                $query_run = mysqli_query($con, $query);

                                if(mysqli_num_rows($query_run) > 0) {
                                    foreach($query_run as $row) {
                                        ?>
                                        <tr>
                                            <td><?= $row['course_code']; ?></td>
                                            <td><?= $row['course_name']; ?></td>
                                            <td>
                                               <?php
                                               $sid = $_SESSION['auth_user']['user_id'];
                                               $cid = $row['id'];
                                               
                                               $check = "SELECT * FROM enrollments WHERE student_id='$sid' AND course_id='$cid'";
                                                  $check_run = mysqli_query($con, $check);

                                                  if(mysqli_num_rows($check_run) > 0) {
                                                    echo '<a href="viewcourse.php?id='.$cid.'" class="btn btn-success btn-sm">View Materials</a>';
                                                    } else {
                                                        echo '<form action="enrollcode.php" method="POST">
                                                        <input type="hidden" name="course_id" value="'.$cid.'">
                                                        <button type="submit" name="enroll_btn" class="btn btn-primary btn-sm">Enroll Now</button>
                                                        </form>';
                                                    }
                                                   ?> 
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center'>No courses available yet.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include('footer.php'); ?>