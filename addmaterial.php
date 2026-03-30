<?php
include('dbcon.php');
include('header.php');

if(!isset($_SESSION['auth']) || $_SESSION['auth_role'] != 'tutor' && $_SESSION['auth_role'] != 'admin') {
    header("Location: login.php");
    exit(0);
}
?>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4>Upload Course Material</h4>
        </div>
        <div class="card-body">
            <form action="uploadcode.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Select Course</label>
                    <select name="course_id" required class="form-control">
                        <option value="">-- Select Unit --</option>
                        <?php
                        $tutor_id = $_SESSION['auth_user']['user_id'];
                        $courses = mysqli_query($con, "SELECT * FROM courses WHERE tutor_id='$tutor_id'");
                        foreach($courses as $course) {
                            echo '<option value="'.$course['id'].'">'.$course['course_code'].' - '.$course['course_name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Material Title (e.g. Week 1 Notes)</label>
                    <input type="text" name="title" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Select File (PDF, DOCX, PPTX)</label>
                    <input type="file" name="course_file" required class="form-control">
                </div>
                <button type="submit" name="upload_btn" class="btn btn-success">Upload Now</button>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>