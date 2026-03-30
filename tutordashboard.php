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

                <!-- quick action buttons -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <a href="addmaterial.php" class="btn btn-success w-100 mb-2">Upload New Content</a>
                    </div>
                    <div class="col-md-4">
                        <a href="submissions.php" class="btn btn-outline-success w-100 mb-2">View Student Submissions</a>
                    </div>
                    </div>

                    <!-- assigned courses table -->
                     <h6>My Assigned Courses</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Code</th>
                                    <th>Course Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include('dbcon.php');
                                $tutor_id = $_SESSION['auth_user']['user_id'];

                                $query = "SELECT * FROM courses WHERE tutor_id=$tutor_id";
                                $query_run = mysqli_query($con, $query);

                                if(mysqli_num_rows($query_run) > 0) {
                                    foreach($query_run as $row) {
                                        ?>
                                        <tr>
                                            <td><?= $row['course_code']; ?></td>
                                            <td><?= $row['course_name']; ?></td>
                                            <td>
                                                <a href="managecourse.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary">Manage</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>No courses assigned yet.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-4 shadow-sm border-info">
    <div class="card-header bg-info text-white">
        <h5>Recently Uploaded Materials</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>File Name</th>
                        <th>Uploaded Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch materials linked to THIS tutor
                    $query = "SELECT materials.*, courses.course_code 
                              FROM materials 
                              INNER JOIN courses ON materials.course_id = courses.id 
                              WHERE courses.tutor_id = '$tutor_id' 
                              ORDER BY materials.uploaded_at DESC";
                    $materials_run = mysqli_query($con, $query);

                    if(mysqli_num_rows($materials_run) > 0) {
                        foreach($materials_run as $material) {
                            ?>
                            <tr>
                                <td><?= $material['title']; ?> (<?= $material['course_code']; ?>)</td>
                                <td>
                                    <a href="uploads/<?= $material['file_name']; ?>" target="_blank" class="text-decoration-none">
                                        <i class="fa fa-file-pdf"></i> View File
                                    </a>
                                </td>
                                <td><?= date('d M Y', strtotime($material['uploaded_at'])); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>No materials uploaded yet.</td></tr>";
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