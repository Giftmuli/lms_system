<?php
include('dbcon.php');
include('header.php');

if (!isset($_SESSION['auth']) || $_SESSION['auth_role'] != 'admin') {
    $_SESSION['message'] = "Admin Access Only!";
    header("Location: index.php");
    exit(0);
}

if(isset($_POST['add_course_btn'])) {
    $course_code = mysqli_real_escape_string($con, $_POST['course_code']);
    $course_name = mysqli_real_escape_string($con, $_POST['course_name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);

    $query = "INSERT INTO courses (course_code, course_name, description)
              VALUES ('$course_code', '$course_name', '$description')";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $_SESSION['message'] = "Course Added Successfully";
        header("Location: admin dashboard.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error: " .mysqli_error($con);
        header("Location: addcourse.php");
        exit(0);
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>Add New Courses</h4>
                </div>
                <div class="card-body">
                    <form action="code.php" method="POST">
                        <div class="mb-3">
                            <label>Course Code</label>
                            <input type="text" name="course_code" placeholder="e.g. DICS 1303" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Course Name</label>
                            <input type="text" name="course_name" placeholder="e.g. Web Development" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" rows="3" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <button type="sumit" name="add_course_btn" class="btn btn-primary">Save Course</button>
                            <a href="admin dashboard.php" class="btn btn-danger">Back to Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
