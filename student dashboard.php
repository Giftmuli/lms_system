<?php
include('header.php');
if(!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "Please login to access the dashboard";
    header("Location: login.php");
    exit(0);
}
?>

    <div class ="container my-5">
        <div class="card shadow-sm">
            <div class="card-header b-primary text-white">
                <h4>Student Dashboard</h4>
            </div>
            <div class="card-body">
                <h5>Welcome, <?=$_SESSION['auth_user']['user_name']?></h5>
                <p>Role: <span class="badge bg-info text-dark"><?=$_SESSION['auth_role']; ?></span></p>
                <hr>
                <div class="alert alert-light border">
                    <h6>My Courses</h6>
                    <p class="text-muted">You are not enrolled in any courses yet.</p>
                </div>
            </div>
        </div>
    </div>

<?php include('footer.php'); ?>