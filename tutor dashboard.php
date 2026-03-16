<?php
include('header.php');

if(
    !isset($_SESSION['auth']) ||
    ($_SESSION['auth_role'] != 'tutor' && $_SESSION['auth_role'] != 'admin')) { 
    $_SESSION['message'] = "Access Denied!";
    header("Location: login.php");
    exit(0);
}
?>

    <div class="container my-5">
        <div class="card shadow-sm border-success">
            <div class="card-header bg-success text-white">
                <h4>Tutor Portal</h4>
            </div>
            <div class="card-body">
                <h5>Hello, Lecturer <?=$_SESSION['auth_user']['user_name']?></h5>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <button class="btn btn-success w-100 mb-2">Upload New Content</button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-success w-100 mb-2">View Student Submissions</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include('footer.php'); ?>