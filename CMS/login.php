<?php
session_start();
require_once 'config/db_connect.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            // Set Session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['is_admin'] = $row['is_admin'];
            
            // Set Cookies for 30 days
            setcookie('remembered_username', $username, time() + (86400 * 30), "/");
            setcookie('remembered_password', $password, time() + (86400 * 30), "/");
            
            // Update login tracking
            $login_count = $row['login_count'] + 1;
            $update_sql = "UPDATE users SET 
                           login_count = $login_count,
                           last_login = NOW(),
                           last_activity = NOW()
                           WHERE id = " . $row['id'];
            mysqli_query($conn, $update_sql);
            
            header("Location: dashboard.php");
            exit();
        }
    }
    $error_message = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Contact Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                            <a href="register.php" class="btn btn-link">Create an account</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
