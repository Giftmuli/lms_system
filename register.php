<?php 
include('dbcon.php'); 
include('header.php'); 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <?php
            if(isset($_POST['register_btn'])) {
                $name = mysqli_real_escape_string($con, $_POST['name']);
                $email = mysqli_real_escape_string($con, $_POST['email']);
                $password = sha1($_POST['password']); 
                $role = $_POST['role'];

                // Insert query for your users table
                $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
                $query_run = mysqli_query($con, $query);

                if($query_run) {
                    echo '<div class="alert alert-success">Registration Successful! <a href="login.php">Login here</a></div>';
                } else {
                    echo '<div class="alert alert-danger">Something went wrong! '.mysqli_error($con).'</div>';
                }
            }
            ?>

            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h2 class="text-center fw-bold mb-4">Join SPU LMS</h2>
                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">I am a...</label>
                            <select name="role" class="form-select" required>
                                <option value="" selected disabled>Choose your role</option>
                                <option value="student">Student</option>
                                <option value="tutor">Tutor</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="register_btn" class="btn btn-primary btn-lg">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>