<?php
session_start();
include('dbcon.php');

if (!isset($_SESSION['auth']) || $_SESSION['auth_role'] != 'student') {
    $_SESSION['message'] = "Unauthorized access!";
    header("Location: login.php");
    exit(0);
}

if (isset($_POST['submit_assignment_btn'])) {
    $course_id = mysqli_real_escape_string($con, $_POST['course_id']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $student_id = $_SESSION['auth_user']['user_id'];

    if (empty($course_id) || empty($title)) {
        $_SESSION['message'] = "Please fill in all fields.";
        header("Location: viewcourse.php?id=" . $course_id);
        exit(0);
    }

    if (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] == 0) {
        $file = $_FILES['submission_file']['name'];
        $file_tmp = $_FILES['submission_file']['tmp_name'];
        $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        // Generate a unique, safe file name
        $new_filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $file);
        
        $allowed_ext = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'zip', 'png', 'jpg', 'jpeg'];

        if (in_array($file_extension, $allowed_ext)) {
            $upload_path = 'uploads/' . $new_filename;

            // Ensure uploads directory exists
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $query = "INSERT INTO submissions (student_id, course_id, title, file_name) VALUES ('$student_id', '$course_id', '$title', '$new_filename')";
                $query_run = mysqli_query($con, $query);

                if ($query_run) {
                    $_SESSION['message'] = "Assignment submitted successfully!";
                } else {
                    $_SESSION['message'] = "Database insertion failed: " . mysqli_error($con);
                    // Remove file if database write fails to keep uploads clean
                    if (file_exists($upload_path)) {
                        unlink($upload_path);
                    }
                }
            } else {
                $_SESSION['message'] = "Failed to move uploaded file.";
            }
        } else {
            $_SESSION['message'] = "Invalid file type. Allowed: " . implode(', ', $allowed_ext);
        }
    } else {
        $_SESSION['message'] = "Please select a valid file to upload. Error Code: " . $_FILES['submission_file']['error'];
    }

    header("Location: viewcourse.php?id=" . $course_id);
    exit(0);
} else {
    $_SESSION['message'] = "Invalid Request!";
    header("Location: studentdashboard.php");
    exit(0);
}
?>
