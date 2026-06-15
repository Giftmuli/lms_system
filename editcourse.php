<?php
include('dbcon.php');
include('header.php');

if (!isset($_SESSION['auth']) || $_SESSION['auth_role'] != 'admin') {
    $_SESSION['message'] = "Admin Access Only!";
    header("Location: index.php");
    exit(0);
}

if (isset($_GET['id'])) {
    $course_id = mysqli_real_escape_string($con, $_GET['id']);
    
    // Fetch course details
    $course_query = "SELECT * FROM courses WHERE id='$course_id' LIMIT 1";
    $course_query_run = mysqli_query($con, $course_query);

    if (mysqli_num_rows($course_query_run) > 0) {
        $course = mysqli_fetch_array($course_query_run);
    } else {
        $_SESSION['message'] = "No such course found!";
        header("Location: admindashboard.php");
        exit(0);
    }
} else {
    $_SESSION['message'] = "Access Denied: No Course Specified";
    header("Location: admindashboard.php");
    exit(0);
}

if (isset($_POST['update_course_btn'])) {
    $course_code = mysqli_real_escape_string($con, $_POST['course_code']);
    $course_name = mysqli_real_escape_string($con, $_POST['course_name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $tutor_id = mysqli_real_escape_string($con, $_POST['tutor_id']);
    
    $tutor_id_val = !empty($tutor_id) ? "'$tutor_id'" : "NULL";

    $update_query = "UPDATE courses SET 
                        course_code = '$course_code', 
                        course_name = '$course_name', 
                        description = '$description', 
                        tutor_id = $tutor_id_val 
                     WHERE id = '$course_id'";

    $update_query_run = mysqli_query($con, $update_query);

    if ($update_query_run) {
        $_SESSION['message'] = "Course Updated Successfully";
        header("Location: admindashboard.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong! " . mysqli_error($con);
        header("Location: editcourse.php?id=" . $course_id);
        exit(0);
    }
}
?>

<div class="container my-5">
    <!-- Messages Alert -->
    <?php if (isset($_SESSION['message'])) : ?>
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <?= $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php unset($_SESSION['message']); endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header btn-primary text-white py-3">
                    <h4 class="mb-0">Edit / Reassign Course</h4>
                </div>
                <div class="card-body p-4">
                    <form action="editcourse.php?id=<?= $course_id; ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Course Code</label>
                            <input type="text" name="course_code" value="<?= htmlspecialchars($course['course_code']); ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Course Name</label>
                            <input type="text" name="course_name" value="<?= htmlspecialchars($course['course_name']); ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="3" class="form-control" required><?= htmlspecialchars($course['description']); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Assign Tutor</label>
                            <select name="tutor_id" class="form-select">
                                <option value="">No Tutor Assigned</option>
                                <?php
                                $tutors_query = "SELECT * FROM users WHERE role='tutor'";
                                $tutors_query_run = mysqli_query($con, $tutors_query);
                                if (mysqli_num_rows($tutors_query_run) > 0) {
                                    foreach ($tutors_query_run as $tutor) {
                                        $selected = ($tutor['id'] == $course['tutor_id']) ? 'selected' : '';
                                        echo '<option value="' . $tutor['id'] . '" ' . $selected . '>' . htmlspecialchars($tutor['name']) . ' (' . htmlspecialchars($tutor['email']) . ')</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" name="update_course_btn" class="btn btn-success px-4">Update Course</button>
                            <a href="admindashboard.php" class="btn btn-danger px-4">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
