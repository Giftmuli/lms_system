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

    $tutor_id = mysqli_real_escape_string($con, $_POST['tutor_id']);
    $tutor_id_val = !empty($tutor_id) ? "'$tutor_id'" : "NULL";

    $query = "INSERT INTO courses (course_code, course_name, description, tutor_id)
              VALUES ('$course_code', '$course_name', '$description', $tutor_id_val)";

    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $_SESSION['message'] = "Course Added Successfully";
        header("Location: admindashboard.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong!";
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
                    <form action="addcourse.php" method="POST">
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
                            <label>Assign Tutor</label>
                            <select name="tutor_id" class="form-select">
                                <option value="">No Tutor Assigned</option>
                                <?php
                                $tutors_query = "SELECT * FROM users WHERE role='tutor'";
                                $tutors_query_run = mysqli_query($con, $tutors_query);
                                if(mysqli_num_rows($tutors_query_run) > 0) {
                                    foreach($tutors_query_run as $tutor) {
                                        echo '<option value="'.$tutor['id'].'">'.htmlspecialchars($tutor['name']).' ('.htmlspecialchars($tutor['email']).')</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="add_course_btn" class="btn btn-primary">Save Course</button>
                            <a href="admindashboard.php" class="btn btn-danger">Back to Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
