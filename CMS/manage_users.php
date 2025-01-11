<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}
require_once 'config/db_connect.php';

// Handle user actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        
        switch($_POST['action']) {
            case 'delete':
                mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
                break;
                
            case 'reset_password':
                $new_password = password_hash('default123', PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET password = '$new_password' WHERE id = $user_id");
                break;
                
            case 'toggle_admin':
                $sql = "UPDATE users SET is_admin = NOT is_admin WHERE id = $user_id";
                mysqli_query($conn, $sql);
                break;
        }
    }
}

// Get users list
$sql = "SELECT id, username, email, first_name, last_name, is_admin, created_at, last_login, login_count 
        FROM users WHERE id != {$_SESSION['user_id']}";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users - Contact Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                <a href="user_activities.php">User Activities</a>
                <a href="manage_users.php" class="active">Manage Users</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Manage Users</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Last Login</th>
                                    <th>Login Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $row['is_admin'] ? 'primary' : 'secondary'; ?>">
                                            <?php echo $row['is_admin'] ? 'Admin' : 'User'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $row['last_login'] ?? 'Never'; ?></td>
                                    <td><?php echo $row['login_count']; ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                            
                                            
                                            
                                            <button type="submit" name="action" value="toggle_admin" 
                                                    class="btn btn-info btn-sm">
                                                <i class="fas fa-user-shield"></i>
                                            </button>
                                            
                                                <td>
                                                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Are you sure you want to delete this user? All their contacts will also be deleted.')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </td>
                                        </form>
                                    </td>
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

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'user_deleted'): ?>
    <div class="alert alert-success">
        User has been successfully deleted.
    </div>
<?php endif; ?>
