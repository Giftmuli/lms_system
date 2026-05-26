<?php
include('dbcon.php');
include('header.php');

if (!isset($_SESSION['auth']) || ($_SESSION['auth_role'] != 'tutor' && $_SESSION['auth_role'] != 'admin')) {
    $_SESSION['message'] = "Lecturer/Admin Access Only!";
    header("Location: login.php");
    exit(0);
}

$tutor_id = $_SESSION['auth_user']['user_id'];
$role = $_SESSION['auth_role'];
$filter_status = isset($_GET['status']) ? mysqli_real_escape_string($con, $_GET['status']) : 'all';

// Build dynamic WHERE clause based on role and filter status
$where_clauses = [];
if ($role == 'tutor') {
    $where_clauses[] = "c.tutor_id = '$tutor_id'";
}

if ($filter_status == 'pending') {
    $where_clauses[] = "s.grade IS NULL";
} elseif ($filter_status == 'graded') {
    $where_clauses[] = "s.grade IS NOT NULL";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}

$query = "SELECT s.*, u.name as student_name, u.email as student_email, c.course_code, c.course_name 
          FROM submissions s 
          INNER JOIN users u ON s.student_id = u.id 
          INNER JOIN courses c ON s.course_id = c.id 
          $where_sql 
          ORDER BY s.submitted_at DESC";

$query_run = mysqli_query($con, $query);
?>

<div class="container my-5">
    <!-- Messages alert -->
    <?php if (isset($_SESSION['message'])) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <?= $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php unset($_SESSION['message']); endif; ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-success text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">Student Submissions Inbox</h4>
                <a href="tutordashboard.php" class="btn btn-outline-light btn-sm">Back to Portal</a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Tabs -->
            <ul class="nav nav-pills mb-4">
                <li class="nav-item">
                    <a class="nav-link <?= $filter_status == 'all' ? 'active bg-success' : 'text-success' ?>" href="submissions.php?status=all">
                        All Submissions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $filter_status == 'pending' ? 'active bg-success' : 'text-success' ?>" href="submissions.php?status=pending">
                        Pending Review
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $filter_status == 'graded' ? 'active bg-success' : 'text-success' ?>" href="submissions.php?status=graded">
                        Graded / Reviewed
                    </a>
                </li>
            </ul>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Course</th>
                            <th>Student</th>
                            <th>Submission Title</th>
                            <th>File</th>
                            <th>Submitted Date</th>
                            <th>Grade Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            foreach ($query_run as $row) {
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($row['course_code']); ?></span>
                                        <div class="small text-muted text-truncate" style="max-width: 150px;"><?= htmlspecialchars($row['course_name']); ?></div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($row['student_name']); ?></div>
                                        <span class="text-muted small"><?= htmlspecialchars($row['student_email']); ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($row['title']); ?></td>
                                    <td>
                                        <a href="uploads/<?= $row['file_name']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            View File
                                        </a>
                                    </td>
                                    <td class="small text-muted"><?= date('d M Y, H:i', strtotime($row['submitted_at'])); ?></td>
                                    <td>
                                        <?php if (is_null($row['grade'])) : ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php else : ?>
                                            <span class="badge bg-success">Graded: <?= htmlspecialchars($row['grade']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" 
                                                class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#gradeModal"
                                                data-sub-id="<?= $row['id']; ?>"
                                                data-course-id="<?= $row['course_id']; ?>"
                                                data-student-name="<?= htmlspecialchars($row['student_name']); ?>"
                                                data-sub-title="<?= htmlspecialchars($row['title']); ?>"
                                                data-grade="<?= htmlspecialchars($row['grade'] ?? ''); ?>"
                                                data-review="<?= htmlspecialchars($row['review'] ?? ''); ?>">
                                            <?= is_null($row['grade']) ? 'Grade' : 'Edit Grade' ?>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted py-3">No submissions found matching this filter.</div>
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

<!-- Grading Modal -->
<div class="modal fade" id="gradeModal" tabindex="-1" aria-labelledby="gradeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <form action="submitreview.php" method="POST">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="gradeModalLabel">Review & Grade Work</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="submission_id" id="modal_submission_id">
                    <input type="hidden" name="course_id" id="modal_course_id">
                    <input type="hidden" name="redirect_to" value="submissions.php?status=<?= $filter_status; ?>">
                    
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
                    <button type="submit" name="submit_review_btn" class="btn btn-success px-4">Submit Review</button>
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
                const button = event.relatedTarget;
                
                const subId = button.getAttribute('data-sub-id');
                const courseId = button.getAttribute('data-course-id');
                const studentName = button.getAttribute('data-student-name');
                const subTitle = button.getAttribute('data-sub-title');
                const grade = button.getAttribute('data-grade');
                const review = button.getAttribute('data-review');

                document.getElementById('modal_submission_id').value = subId;
                document.getElementById('modal_course_id').value = courseId;
                document.getElementById('modal_student_name').value = studentName;
                document.getElementById('modal_submission_title').value = subTitle;
                document.getElementById('modal_grade').value = grade || '';
                document.getElementById('modal_review').value = review || '';
            });
        }
    });
</script>

<?php include('footer.php'); ?>
