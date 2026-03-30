<?php
session_start();
include('dbcon.php');

if(isset($_POST['enroll_btn'])) {
    $student_id = $_SESSION['auth_user']['user_id'];
    $course_id = mysqli_real_escape_string($con, $_POST['course_id']);

    // Prevent duplicate enrollments just in case
    $check_query = "SELECT * FROM enrollments WHERE student_id='$student_id' AND course_id='$course_id'";
    $check_run = mysqli_query($con, $check_query);

    if(mysqli_num_rows($check_run) > 0) {
        $_SESSION['message'] = "You are already enrolled in this course!";
        header("Location: student_dashboard.php");
        exit(0);
    }

    $query = "INSERT INTO enrollments (student_id, course_id) VALUES ('$student_id', '$course_id')";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $_SESSION['message'] = "Enrolled successfully!";
        header("Location: student_dashboard.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Enrollment failed. Please try again.";
        header("Location: student_dashboard.php");
        exit(0);
    }
}
?>