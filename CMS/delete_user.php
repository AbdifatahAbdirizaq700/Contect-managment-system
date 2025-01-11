<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}
require_once 'config/db_connect.php';

if(isset($_GET['id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Delete user's contacts first
    mysqli_query($conn, "DELETE FROM contacts WHERE user_id = $user_id");
    
    // Delete user's activities
    mysqli_query($conn, "DELETE FROM user_activities WHERE user_id = $user_id");
    
    // Delete the user
    mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
    
    header("Location: manage_users.php?msg=user_deleted");
    exit();
}
?>
