<?php
session_start();
include('dbcon.php');

if (!isset($_SESSION['auth']) || $_SESSION['auth_role'] != 'admin') {
    $_SESSION['message'] = "Admin Access Only!";
    header("Location: index.php");
    exit(0);
}

if (isset($_GET['id'])) {
    $course_id = mysqli_real_escape_string($con, $_GET['id']);

    // Check if the course exists
    $course_check = mysqli_query($con, "SELECT id FROM courses WHERE id='$course_id' LIMIT 1");
    if (mysqli_num_rows($course_check) > 0) {
        
        // 1. Delete associated files from the uploads directory
        // A. Material files
        $materials_query = "SELECT file_name FROM materials WHERE course_id='$course_id'";
        $materials_run = mysqli_query($con, $materials_query);
        while ($material = mysqli_fetch_assoc($materials_run)) {
            $filepath = 'uploads/' . $material['file_name'];
            if (file_exists($filepath) && !empty($material['file_name'])) {
                unlink($filepath);
            }
        }

        // B. Submission files
        $submissions_query = "SELECT file_name FROM submissions WHERE course_id='$course_id'";
        $submissions_run = mysqli_query($con, $submissions_query);
        while ($submission = mysqli_fetch_assoc($submissions_run)) {
            $filepath = 'uploads/' . $submission['file_name'];
            if (file_exists($filepath) && !empty($submission['file_name'])) {
                unlink($filepath);
            }
        }

        // 2. Delete related records in database tables
        mysqli_query($con, "DELETE FROM enrollments WHERE course_id='$course_id'");
        mysqli_query($con, "DELETE FROM materials WHERE course_id='$course_id'");
        mysqli_query($con, "DELETE FROM submissions WHERE course_id='$course_id'");
        
        // 3. Delete the course record
        $delete_course = "DELETE FROM courses WHERE id='$course_id'";
        $delete_course_run = mysqli_query($con, $delete_course);

        if ($delete_course_run) {
            $_SESSION['message'] = "Course and all its associated data deleted successfully";
        } else {
            $_SESSION['message'] = "Failed to delete course: " . mysqli_error($con);
        }
    } else {
        $_SESSION['message'] = "Course not found!";
    }
} else {
    $_SESSION['message'] = "Access Denied: No Course Specified";
}

header("Location: admindashboard.php");
exit(0);
?>
