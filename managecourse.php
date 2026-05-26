<?php
include('dbcon.php');
include('header.php');

if (!isset($_SESSION['auth']) || ($_SESSION['auth_role'] != 'tutor' && $_SESSION['auth_role'] != 'admin')) {
    $_SESSION['message'] = "Lecturer/Admin Access Only!";
    header("Location: login.php");
    exit(0);
}

if (isset($_GET['id'])) {
    $course_id = mysqli_real_escape_string($con, $_GET['id']);
    $tutor_id = $_SESSION['auth_user']['user_id'];
    $role = $_SESSION['auth_role'];

    // If role is tutor, ensure they own/are assigned to this course
    if ($role == 'tutor') {
        $course_query = "SELECT * FROM courses WHERE id='$course_id' AND tutor_id='$tutor_id' LIMIT 1";
    } else {
        $course_query = "SELECT * FROM courses WHERE id='$course_id' LIMIT 1";
    }

    $course_run = mysqli_query($con, $course_query);

    if (mysqli_num_rows($course_run) > 0) {
        $course = mysqli_fetch_array($course_run);
        
        // Count metrics
        $enroll_count_res = mysqli_query($con, "SELECT COUNT(*) AS total FROM enrollments WHERE course_id='$course_id'");
        $enroll_count = mysqli_fetch_array($enroll_count_res)['total'];

        $sub_count_res = mysqli_query($con, "SELECT COUNT(*) AS total FROM submissions WHERE course_id='$course_id'");
        $sub_count = mysqli_fetch_array($sub_count_res)['total'];

        $pending_count_res = mysqli_query($con, "SELECT COUNT(*) AS total FROM submissions WHERE course_id='$course_id' AND grade IS NULL");
        $pending_count = mysqli_fetch_array($pending_count_res)['total'];

        $graded_count = $sub_count - $pending_count;
        ?>

        <div class="container my-5">
            <!-- Messages alert -->
            <?php if (isset($_SESSION['message'])) : ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <?= $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php unset($_SESSION['message']); endif; ?>

            <!-- Course Header Card -->
            <div class="card shadow-sm border-0 mb-4 bg-dark text-white rounded-3">
                <div class="card-body p-4 p-md-5">
                    <div class="d-md-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-primary px-3 py-2 mb-2"><?= htmlspecialchars($course['course_code']); ?></span>
                            <h1 class="display-5 fw-bold"><?= htmlspecialchars($course['course_name']); ?></h1>
                            <p class="lead mb-0 text-white-50"><?= htmlspecialchars($course['description']); ?></p>
                        </div>
                        <div class="mt-3 mt-md-0">
                            <a href="tutordashboard.php" class="btn btn-outline-light me-2">Back to Dashboard</a>
                            <a href="addmaterial.php" class="btn btn-success">Upload Material</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Statistics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center py-3 bg-light">
                        <h3 class="fw-bold text-dark mb-1"><?= $enroll_count; ?></h3>
                        <span class="text-muted small">Enrolled Students</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center py-3 bg-light">
                        <h3 class="fw-bold text-dark mb-1"><?= $sub_count; ?></h3>
                        <span class="text-muted small">Total Submissions</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center py-3 bg-light">
                        <h3 class="fw-bold text-warning mb-1"><?= $pending_count; ?></h3>
                        <span class="text-warning small">Pending Review</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 text-center py-3 bg-light">
                        <h3 class="fw-bold text-success mb-1"><?= $graded_count; ?></h3>
                        <span class="text-success small">Graded / Reviewed</span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Submissions Table Area -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3">
                            <h4 class="fw-bold text-primary mb-0">Student Submissions</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="ps-3">Student</th>
                                            <th>Submission Details</th>
                                            <th>File</th>
                                            <th>Date Submitted</th>
                                            <th>Grade / Status</th>
                                            <th class="pe-3 text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $subs_query = "SELECT s.*, u.name as student_name, u.email as student_email 
                                                       FROM submissions s 
                                                       INNER JOIN users u ON s.student_id = u.id 
                                                       WHERE s.course_id = '$course_id' 
                                                       ORDER BY s.submitted_at DESC";
                                        $subs_run = mysqli_query($con, $subs_query);

                                        if (mysqli_num_rows($subs_run) > 0) {
                                            foreach ($subs_run as $sub) {
                                                ?>
                                                <tr>
                                                    <td class="ps-3">
                                                        <div class="fw-semibold"><?= htmlspecialchars($sub['student_name']); ?></div>
                                                        <span class="text-muted small"><?= htmlspecialchars($sub['student_email']); ?></span>
                                                    </td>
                                                    <td><?= htmlspecialchars($sub['title']); ?></td>
                                                    <td>
                                                        <a href="uploads/<?= $sub['file_name']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            View File
                                                        </a>
                                                    </td>
                                                    <td class="text-muted small">
                                                        <?= date('d M Y, H:i', strtotime($sub['submitted_at'])); ?>
                                                    </td>
                                                    <td>
                                                        <?php if (is_null($sub['grade'])) : ?>
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        <?php else : ?>
                                                            <span class="badge bg-success">Graded: <?= htmlspecialchars($sub['grade']); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="pe-3 text-end">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#gradeModal"
                                                                data-sub-id="<?= $sub['id']; ?>"
                                                                data-student-name="<?= htmlspecialchars($sub['student_name']); ?>"
                                                                data-sub-title="<?= htmlspecialchars($sub['title']); ?>"
                                                                data-grade="<?= htmlspecialchars($sub['grade'] ?? ''); ?>"
                                                                data-review="<?= htmlspecialchars($sub['review'] ?? ''); ?>">
                                                            <?= is_null($sub['grade']) ? 'Grade' : 'Edit Grade' ?>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <div class="text-muted">No student submissions found for this course.</div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enrolled Students Sidebar -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold text-dark mb-0">Enrolled Students</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <?php
                                $enroll_query = "SELECT e.enrolled_at, u.name, u.email 
                                                 FROM enrollments e 
                                                 INNER JOIN users u ON e.student_id = u.id 
                                                 WHERE e.course_id = '$course_id' 
                                                 ORDER BY u.name ASC";
                                $enroll_run = mysqli_query($con, $enroll_query);

                                if (mysqli_num_rows($enroll_run) > 0) {
                                    foreach ($enroll_run as $student) {
                                        ?>
                                        <li class="list-group-item px-0 py-2 border-0 border-bottom">
                                            <div class="fw-semibold"><?= htmlspecialchars($student['name']); ?></div>
                                            <div class="text-muted small d-flex justify-content-between">
                                                <span><?= htmlspecialchars($student['email']); ?></span>
                                                <span>Joined: <?= date('d M Y', strtotime($student['enrolled_at'])); ?></span>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                } else {
                                    echo '<div class="text-muted text-center py-3">No students enrolled yet.</div>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grading Modal -->
        <div class="modal fade" id="gradeModal" tabindex="-1" aria-labelledby="gradeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow-lg">
                    <form action="submitreview.php" method="POST">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title fw-bold" id="gradeModalLabel">Review & Grade Work</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="submission_id" id="modal_submission_id">
                            <input type="hidden" name="course_id" value="<?= $course_id; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Student Name</label>
                                <input type="text" id="modal_student_name" class="form-control bg-light" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Submission Title</label>
                                <input type="text" id="modal_submission_title" class="form-control bg-light" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Grade / Score</label>
                                <input type="text" name="grade" id="modal_grade" class="form-control" placeholder="e.g. A, B, 95%, or Pass" required>
                                <div class="form-text">You can enter a letter grade or numerical percentage.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Review Comments & Feedback</label>
                                <textarea name="review" id="modal_review" class="form-control" rows="4" placeholder="Provide constructive feedback here..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit_review_btn" class="btn btn-primary px-4">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const gradeModal = document.getElementById('gradeModal');
                if (gradeModal) {
                    gradeModal.addEventListener('show.bs.modal', function (event) {
                        // Button that triggered the modal
                        const button = event.relatedTarget;
                        
                        // Extract info from data-attributes
                        const subId = button.getAttribute('data-sub-id');
                        const studentName = button.getAttribute('data-student-name');
                        const subTitle = button.getAttribute('data-sub-title');
                        const grade = button.getAttribute('data-grade');
                        const review = button.getAttribute('data-review');

                        // Update the modal's content
                        document.getElementById('modal_submission_id').value = subId;
                        document.getElementById('modal_student_name').value = studentName;
                        document.getElementById('modal_submission_title').value = subTitle;
                        document.getElementById('modal_grade').value = grade || '';
                        document.getElementById('modal_review').value = review || '';
                    });
                }
            });
        </script>

        <?php
    } else {
        echo "<div class='container my-5'><h4>Course Not Found or Access Denied!</h4></div>";
    }
} else {
    echo "<div class='container my-5'><h4>Access Denied: No Course Specified</h4></div>";
}

include('footer.php');
?>
