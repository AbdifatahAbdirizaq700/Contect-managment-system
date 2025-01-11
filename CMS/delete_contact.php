<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'config/db_connect.php';

if(isset($_GET['id'])) {
    $contact_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    
    // Delete contact image if exists
    $sql = "SELECT image FROM contacts WHERE id = $contact_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    if($row = mysqli_fetch_assoc($result)) {
        if(!empty($row['image']) && file_exists($row['image'])) {
            unlink($row['image']);
        }
    }
    
    // Delete contact record
    $sql = "DELETE FROM contacts WHERE id = $contact_id AND user_id = $user_id";
    mysqli_query($conn, $sql);
}

header("Location: my_contacts.php");
exit();
?>
