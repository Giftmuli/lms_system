<?php 
include('header.php'); 
?>

<div class="container my-5">
    <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-3 border shadow-lg bg-white">
        <div class="col-lg-7 p-3 p-lg-5 pt-lg-3">
            <h1 class="display-4 fw-bold lh-1">St. Paul's University LMS</h1>
            <p class="lead mt-4">
                Welcome to the official Learning Management System for St. Paul's University. 
                Access your course materials, track your learning progress, and stay connected 
                with your tutors in a centralized digital environment
            </p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4 mb-lg-3 mt-4">
                <a href="register.php" class="btn btn-primary btn-lg px-4 me-md-2 fw-bold">Create Account</a>
                <a href="login.php" class="btn btn-outline-secondary btn-lg px-4">Login</a>
            </div>
        </div>
        <div class="col-lg-4 offset-lg-1 p-0 overflow-hidden shadow-lg">
            <img class="rounded-lg-3" src="images/hero.jpg" alt="LMS Hero" width="720" onerror="this.src='https://via.placeholder.com/720x500/800000/FFFFFF?text=SPU+LMS'">
        </div>
    </div>
</div>

<div class="container px-4 py-5" id="custom-cards">
    <h2 class="pb-2 border-bottom">Our Modules</h2>
    <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
        
        <div class="col">
            <div class="card card-cover h-100 overflow-hidden text-white bg-dark rounded-4 shadow-lg p-4">
                <h3 class="pt-2 mt-2 mb-4 display-6 lh-1 fw-bold">Students</h3>
                <p>Enroll in courses, view lessons, and submit assignments online</p>
            </div>
        </div>

        <div class="col">
            <div class="card card-cover h-100 overflow-hidden text-white bg-primary rounded-4 shadow-lg p-4">
                <h3 class="pt-2 mt-2 mb-4 display-6 lh-1 fw-bold">Tutors</h3>
            <p>Manage course content, upload PDFs, and track student submissions</p>
            </div>
        </div>

        <div class="col">
            <div class="card card-cover h-100 overflow-hidden text-white bg-secondary rounded-4 shadow-lg p-4">
                <h3 class="pt-2 mt-2 mb-4 display-6 lh-1 fw-bold">Admins</h3>
                <p>Full control over user management and system reports</p>
            </div>
        </div>

    </div>
</div>

<?php include('footer.php'); ?>

<script src="bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>