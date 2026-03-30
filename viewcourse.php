<?php
include('dbcon.php');
include('header.php');

if(isset($_GET['id'])) {
    $course_id = mysqli_real_escape_string($con, $_GET['id']);
    
    $query = "SELECT * FROM courses WHERE id='$course_id' LIMIT 1";
    $query_run = mysqli_query($con, $query);

    if(mysqli_num_rows($query_run) > 0) {
        $course = mysqli_fetch_array($query_run);
        ?>
        <div class="container my-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4><?= $course['course_code']; ?>: <?= $course['course_name']; ?></h4>
                </div>
                <div class="card-body">
                    <h5>Course Description</h5>
                    <p class="lead"><?= $course['description']; ?></p>
                    <hr>
                    <h5>Study Materials</h5>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> No materials uploaded for this unit yet.
                    </div>
                    <a href="student_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "<div class='container my-5'><h4>No Course Found</h4></div>";
    }
} else {
    echo "<div class='container my-5'><h4>Access Denied: No Course Selected</h4></div>";
}

include('footer.php');
?>