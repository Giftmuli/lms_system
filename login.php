<?php 
include('header.php'); 
?>

<?php
include('dbcon.php');

if(isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = sha1($_POST['password']); 

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
    $query_run = mysqli_query($con, $query);

    if(mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_array($query_run);

        // Store user data in Session variables
        $_SESSION['auth'] = true;
        $_SESSION['auth_role'] = $row['role']; // 'student', 'tutor', or 'admin'
        $_SESSION['auth_user'] = [
            'user_id' => $row['id'],
            'user_name' => $row['name'],
            'user_email' => $row['email'],
        ];

        // Redirect based on role
        if($_SESSION['auth_role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit(0);
    } else {
        $_SESSION['message'] = "Invalid Email or Password";
        header("Location: login.php");
        exit(0);
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4 fw-bold">Login</h2>
                    <p class="text-center text-muted mb-4">Access the SPU Learning Management System</p>
                    
                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter your password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me on this device</label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" name="login_btn" class="btn btn-primary btn-lg">Login</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white border-0 text-center pb-4">
                    <p class="text-muted">Don't have an account? <a href="register.php" class="text-primary text-decoration-none fw-bold">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include('footer.php'); 
?>