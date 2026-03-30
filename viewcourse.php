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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Topic / Title</th>
                                    <th>File</th>
                                    <th>Uploaded Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch files linked to this specific course
                                $materials_query = "SELECT * FROM materials WHERE course_id = '$course_id' ORDER BY uploaded_at DESC";
                                $materials_run = mysqli_query($con, $materials_query);

                                if(mysqli_num_rows($materials_run) > 0) {
                                    foreach($materials_run as $file) {
                                        ?>
                                        <tr>
                                            <td><?= $file['title']; ?></td>
                                            <td>
                                                <a href="uploads/<?= $file['file_name']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    View / Download
                                                </a>
                                            </td>
                                            <td><?= date('d M Y', strtotime($file['uploaded_at'])); ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <div class="alert alert-info mb-0">
                                                No materials uploaded for this unit yet.
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="studentdashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                    </div>
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