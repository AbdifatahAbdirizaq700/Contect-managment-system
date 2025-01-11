<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}
require_once 'config/db_connect.php';

// Get all users' login activities
$sql = "SELECT 
    u.username,
    u.first_name,
    u.last_name,
    u.login_count,
    u.last_login,
    u.last_activity,
    u.created_at
    FROM users u 
    ORDER BY u.last_activity DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Activities - Contact Management System</title>
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
                <a href="user_activities.php" class="active">User Activities</a>
                <a href="manage_users.php">Manage Users</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">User Login Activities</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Full Name</th>
                                    <th>Login Count</th>
                                    <th>Last Login</th>
                                    <th>Last Activity</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                        <td><?php echo $row['login_count']; ?></td>
                                        <td><?php echo $row['last_login'] ? date('Y-m-d H:i:s', strtotime($row['last_login'])) : 'Never'; ?></td>
                                        <td><?php echo $row['last_activity'] ? date('Y-m-d H:i:s', strtotime($row['last_activity'])) : 'Never'; ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
