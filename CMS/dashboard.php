<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'config/db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Contact Management System</title>
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
                <a href="add_contact.php">Add New Contact</a>
                <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <a href="user_activities.php">User Activities</a>
                    <a href="manage_users.php">Manage Users</a>
                <?php endif; ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <h4>Welcome to Dashboard</h4>
                <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Users</h5>
                                    <?php
                                    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
                                    $row = mysqli_fetch_assoc($result);
                                    echo "<p class='card-text h2'>" . $row['count'] . "</p>";
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Contacts</h5>
                                    <?php
                                    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM contacts");
                                    $row = mysqli_fetch_assoc($result);
                                    echo "<p class='card-text h2'>" . $row['count'] . "</p>";
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                <?php endif; ?>
                
                
            </div>
        </div>
    </div>
</body>
</html>
