<?php
include('dbcon.php');
include('header.php');

if (!isset($_SESSION['auth']) || $_SESSION['auth_role'] != 'admin') {
    $_SESSION['message'] = "Admin Access Only!";
    header("Location: index.php");
    exit(0);
}
?>

    <div class="container my-5">
        <div class="card shadow-sm border-danger">
            <div class="card-header btn-primary text-white">
                <h4>Admin Control Centre</h4>
            </div>
            <h5>System Overview: Welcome, Admin <?=$_SESSION['auth_user']['user_name']?></h5>
            <hr>
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="p-3 border bg-light">
                        <h6>Total Users</h6>
                        <h3>
                            <?php
                            $user_query = "SELECT id FROM users";
                            $user_query_run = mysqli_query($con, $user_query);
                            echo mysqli_num_rows($user_query_run);
                            ?>
                        </h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border bg-light">
                        <h6>Total Courses</h6>
                        <h3>
                            <?php
                            $course_query = "SELECT id FROM courses";
                            $course_query_run = mysqli_query($con, $course_query);
                            if($course_query_run) {
                                echo mysqli_num_rows($course_query_run);
                            } else {
                                echo "0";
                            }
                            ?>
                        </h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="addcourse.php" class="btn btn-success w-100 mb-2">Add New Course</a>
                    <a href="register.php" class="btn btn-primary w-100">Add New User</a>
                </div>
            </div>
        </div>
    </div>

<?php include('footer.php'); ?>
               