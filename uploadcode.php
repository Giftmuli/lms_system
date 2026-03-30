<?php
session_start();
include('dbcon.php');

if(isset($_POST['upload_btn'])) {
    $course_id = $_POST['course_id'];
    $title = mysqli_real_escape_string($con, $_POST['title']);
    
    $file = $_FILES['course_file']['name'];
    $file_extension = pathinfo($file, PATHINFO_EXTENSION);
    $filename = time() . '.' . $file_extension; // Renames file to prevent overwriting

    $allowed_ext = ['pdf', 'docx', 'pptx', 'txt'];

    if(in_array($file_extension, $allowed_ext)) {
        $query = "INSERT INTO materials (course_id, title, file_name) VALUES ('$course_id', '$title', '$filename')";
        $query_run = mysqli_query($con, $query);

        if($query_run) {
            move_uploaded_file($_FILES['course_file']['tmp_name'], 'uploads/'.$filename);
            $_SESSION['message'] = "Material Uploaded Successfully!";
            header("Location: tutordashboard.php");
            exit(0);
        } else {
            $_SESSION['message'] = "Database Error!";
            header("Location: addmaterial.php");
        }
    } else {
        $_SESSION['message'] = "Invalid File Format!";
        header("Location: addmaterial.php");
    }
}
?>