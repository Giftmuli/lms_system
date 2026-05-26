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
            <?php if(isset($_SESSION['message'])) : ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?= $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php unset($_SESSION['message']); endif; ?>

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

                    <?php if (isset($_SESSION['auth_role']) && $_SESSION['auth_role'] == 'student') : ?>
                        <hr class="my-5">
                        
                        <div class="row">
                            <div class="col-md-7">
                                <h5 class="fw-bold text-primary mb-3">My Submissions</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Title</th>
                                                <th>File</th>
                                                <th>Submitted Date</th>
                                                <th>Status & Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $student_id = $_SESSION['auth_user']['user_id'];
                                            $subs_query = "SELECT * FROM submissions WHERE course_id = '$course_id' AND student_id = '$student_id' ORDER BY submitted_at DESC";
                                            $subs_run = mysqli_query($con, $subs_query);

                                            if (mysqli_num_rows($subs_run) > 0) {
                                                foreach ($subs_run as $sub) {
                                                    ?>
                                                    <tr>
                                                        <td class="align-middle fw-semibold"><?= htmlspecialchars($sub['title']); ?></td>
                                                        <td class="align-middle">
                                                            <a href="uploads/<?= $sub['file_name']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                                View Submission
                                                            </a>
                                                        </td>
                                                        <td class="align-middle text-muted"><?= date('d M Y, H:i', strtotime($sub['submitted_at'])); ?></td>
                                                        <td class="align-middle">
                                                            <?php if (is_null($sub['grade'])) : ?>
                                                                <span class="badge bg-warning text-dark px-3 py-2">Pending Review</span>
                                                            <?php else : ?>
                                                                <span class="badge bg-success px-3 py-2 mb-2">Graded: <?= htmlspecialchars($sub['grade']); ?></span>
                                                                <?php if (!empty($sub['review'])) : ?>
                                                                    <div class="small text-muted bg-light p-2 rounded border">
                                                                        <strong>Lecturer Review:</strong> <?= nl2br(htmlspecialchars($sub['review'])); ?>
                                                                        <br><small class="text-secondary">Reviewed on: <?= date('d M Y', strtotime($sub['reviewed_at'])); ?></small>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">
                                                        <div class="alert alert-light border mb-0 text-muted">
                                                            No submissions made for this course yet.
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="col-md-5">
                                <h5 class="fw-bold text-primary mb-3">Submit Assignment</h5>
                                <div class="card shadow-sm border-0 bg-light p-3">
                                    <form action="submitassignment.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="course_id" value="<?= $course_id; ?>">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Submission Title</label>
                                            <input type="text" name="title" placeholder="e.g. Assignment 1 - Part A" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Choose File (PDF, DOC, DOCX, PPTX, TXT, etc.)</label>
                                            <input type="file" name="submission_file" class="form-control" required>
                                        </div>
                                        <button type="submit" name="submit_assignment_btn" class="btn btn-primary w-100 py-2 fw-semibold">Upload & Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

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