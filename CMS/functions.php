<?php
function log_activity($conn, $user_id, $activity_type, $description) {
    $activity_type = mysqli_real_escape_string($conn, $activity_type);
    $description = mysqli_real_escape_string($conn, $description);
    
    $sql = "INSERT INTO user_activities (user_id, activity_type, activity_description, created_at) 
            VALUES ($user_id, '$activity_type', '$description', NOW())";
    return mysqli_query($conn, $sql);
}

function check_admin() {
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        header("Location: dashboard.php");
        exit();
    }
}

function update_last_activity($conn, $user_id) {
    $sql = "UPDATE users SET last_activity = NOW() WHERE id = $user_id";
    return mysqli_query($conn, $sql);
}
?>
