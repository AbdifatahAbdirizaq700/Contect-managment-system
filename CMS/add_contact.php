<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $user_id = $_SESSION['user_id'];
    
    // Image Upload Processing
    $image_path = "";
    if(isset($_FILES['contact_image']) && $_FILES['contact_image']['error'] == 0) {
        $upload_dir = 'uploads/';
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['contact_image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array(strtolower($filetype), $allowed)) {
            // Create unique filename
            $new_filename = uniqid() . '.' . $filetype;
            $upload_path = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES['contact_image']['tmp_name'], $upload_path)) {
                $image_path = $upload_path;
            }
        }
    }
    
    $sql = "INSERT INTO contacts (user_id, name, email, phone, address, image) 
            VALUES ('$user_id', '$name', '$email', '$phone', '$address', '$image_path')";

    if (mysqli_query($conn, $sql)) {
        header("Location: my_contacts.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Contact - Contact Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Contact Management System</a>
            <div class="navbar-nav ml-auto">
                <span class="navbar-text mr-3">Welcome, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-md-2 sidebar">
            <a href="dashboard.php" class="active">Dashboard</a>
                <a href="my_contacts.php">My Contacts</a>
                <a href="add_contact.php" class="active">Add New Contact</a>
                <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <a href="user_activities.php">User Activities</a>
                    <a href="manage_users.php">Manage Users</a>
                <?php endif; ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Add New Contact</h5>
                    </div>
                    <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Profile Image</label>
                        <input type="file" name="contact_image" class="form-control-file" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Contact</button>
                    <a href="my_contacts.php" class="btn btn-secondary">Cancel</a>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
