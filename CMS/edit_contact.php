<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'config/db_connect.php';

$contact_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $image_path = "";
    if(isset($_FILES['contact_image']) && $_FILES['contact_image']['error'] == 0) {
        $upload_dir = 'uploads/';
        
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['contact_image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array(strtolower($filetype), $allowed)) {
            $new_filename = uniqid() . '.' . $filetype;
            $upload_path = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES['contact_image']['tmp_name'], $upload_path)) {
                // Delete old image if exists
                $sql = "SELECT image FROM contacts WHERE id = $contact_id AND user_id = $user_id";
                $result = mysqli_query($conn, $sql);
                if($row = mysqli_fetch_assoc($result)) {
                    if(!empty($row['image']) && file_exists($row['image'])) {
                        unlink($row['image']);
                    }
                }
                $image_path = $upload_path;
            }
        }
    }
    
    $sql = "UPDATE contacts SET 
            name = '$name',
            email = '$email',
            phone = '$phone',
            address = '$address'";
            
    if($image_path) {
        $sql .= ", image = '$image_path'";
    }
    
    $sql .= " WHERE id = $contact_id AND user_id = $user_id";
    
    if(mysqli_query($conn, $sql)) {
        header("Location: my_contacts.php");
        exit();
    }
}

// Fetch contact data
$sql = "SELECT * FROM contacts WHERE id = $contact_id AND user_id = $user_id";
$result = mysqli_query($conn, $sql);
$contact = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Contact - Contact Management System</title>
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
                <a href="dashboard.php">Dashboard</a>
                <a href="my_contacts.php">My Contacts</a>
                <a href="add_contact.php">Add New Contact</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Contact</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $contact['name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $contact['email']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo $contact['phone']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" class="form-control" rows="3"><?php echo $contact['address']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Profile Image</label>
                                <?php if(!empty($contact['image'])): ?>
                                    <div class="mb-2">
                                        <img src="<?php echo $contact['image']; ?>" class="img-thumbnail" width="100">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="contact_image" class="form-control-file" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Contact</button>
                            <a href="my_contacts.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
