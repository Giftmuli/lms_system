<?php
include('dbcon.php');
include('header.php');

if (!isset($_SESSION['auth']) || $_SESSION['auth_role'] != 'admin') {
    $_SESSION['message'] = "Admin Access Only!";
    header("Location: index.php");
    exit(0);
}
?>

    <div class="container my-5">
        <!-- Messages Alert -->
        <?php if(isset($_SESSION['message'])) : ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['message']); endif; ?>

        <div class="card shadow-sm border-danger">
            <div class="card-header btn-primary text-white py-3">
                <h4 class="mb-0">Admin Control Centre</h4>
            </div>
            <div class="card-body p-4">
                <h5 class="mb-3">System Overview: Welcome, Admin <?=$_SESSION['auth_user']['user_name']?></h5>
                <hr>
                <div class="row text-center mb-4">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="p-3 border bg-light rounded shadow-sm">
                            <h6>Total Users</h6>
                            <h3 class="fw-bold text-primary">
                                <?php
                                $user_query = "SELECT id FROM users";
                                $user_query_run = mysqli_query($con, $user_query);
                                echo mysqli_num_rows($user_query_run);
                                ?>
                            </h3>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="p-3 border bg-light rounded shadow-sm">
                            <h6>Total Courses</h6>
                            <h3 class="fw-bold text-success">
                                <?php
                                $course_query = "SELECT id FROM courses";
                                $course_query_run = mysqli_query($con, $course_query);
                                if($course_query_run) {
                                    echo mysqli_num_rows($course_query_run);
                                } else {
                                    echo "0";
                                }
                                ?>
                            </h3>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex flex-column justify-content-center">
                        <a href="addcourse.php" class="btn btn-success w-100 mb-2">Add New Course</a>
                        <a href="register.php" class="btn btn-primary w-100">Add New User</a>
                    </div>
                </div>

                <div class="mt-5">
                    <h5 class="fw-bold text-primary">Course & Tutor Management</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle border">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3">Course Code</th>
                                    <th>Course Name</th>
                                    <th>Assigned Tutor</th>
                                    <th class="pe-3 text-center" style="width: 220px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT c.*, u.name AS tutor_name, u.email AS tutor_email 
                                          FROM courses c 
                                          LEFT JOIN users u ON c.tutor_id = u.id 
                                          ORDER BY c.course_code ASC";
                                $query_run = mysqli_query($con, $query);

                                if(mysqli_num_rows($query_run) > 0) {
                                    foreach($query_run as $row) {
                                        ?>
                                        <tr>
                                            <td class="ps-3 fw-bold text-primary"><?= htmlspecialchars($row['course_code']); ?></td>
                                            <td><?= htmlspecialchars($row['course_name']); ?></td>
                                            <td>
                                                <?php if($row['tutor_id']): ?>
                                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($row['tutor_name']); ?></div>
                                                    <span class="text-muted small"><?= htmlspecialchars($row['tutor_email']); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">No tutor assigned</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="pe-3 text-center">
                                                <a href="editcourse.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary me-1">Edit / Reassign</a>
                                                <a href="deletecourse.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this course and all its related materials, submissions, and student enrollments?');" class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center py-4 text-muted'>No courses found. Click 'Add New Course' to create one.</td></tr>";
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
               