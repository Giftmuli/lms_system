<?php
session_start();
include('dbcon.php');

if (!isset($_SESSION['auth']) || ($_SESSION['auth_role'] != 'tutor' && $_SESSION['auth_role'] != 'admin')) {
    $_SESSION['message'] = "Unauthorized access!";
    header("Location: login.php");
    exit(0);
}

if (isset($_POST['submit_review_btn'])) {
    $submission_id = mysqli_real_escape_string($con, $_POST['submission_id']);
    $course_id = mysqli_real_escape_string($con, $_POST['course_id']);
    $grade = mysqli_real_escape_string($con, $_POST['grade']);
    $review = mysqli_real_escape_string($con, $_POST['review']);
    $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : 'managecourse.php?id=' . $course_id;

    if (empty($submission_id)) {
        $_SESSION['message'] = "Invalid Submission ID.";
        header("Location: tutordashboard.php");
        exit(0);
    }

    $query = "UPDATE submissions SET 
                grade = '$grade', 
                review = '$review', 
                reviewed_at = CURRENT_TIMESTAMP 
              WHERE id = '$submission_id'";
              
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Submission graded and reviewed successfully!";
    } else {
        $_SESSION['message'] = "Failed to update review: " . mysqli_error($con);
    }

    header("Location: " . $redirect_to);
    exit(0);
} else {
    $_SESSION['message'] = "Invalid Request!";
    header("Location: tutordashboard.php");
    exit(0);
}
?>
